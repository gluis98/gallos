<?php

namespace App\Http\Controllers;

use App\Models\Gallina;
use App\Models\Gallo;
use App\Models\MarketplaceChat;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceRating;
use App\Models\Mensaje;
use App\Models\Publicacion;
use App\Services\DollarRateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $q = Publicacion::query()
            ->withoutGlobalScopes()
            ->where('activo', true)
            ->orderByDesc('destacado')
            ->orderByDesc('created_at');

        if ($request->filled('precio_max')) {
            $q->where('precio', '<=', $request->precio_max);
        }
        if ($request->filled('precio_min')) {
            $q->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('tipo')) {
            $type = $request->input('tipo') === 'gallina' ? Gallina::class : Gallo::class;
            $q->where('ave_type', $type);
        }
        if ($request->filled('color')) {
            $this->restrictByAve(
                $q,
                fn (Builder $sub, string $t) => $sub->where('color', 'like', $t)->orWhere('color_alternativo', 'like', $t),
                (string) $request->input('color')
            );
        }
        if ($request->filled('cresta')) {
            $this->restrictByAve(
                $q,
                fn (Builder $sub, string $t) => $sub->where('cresta', 'like', $t),
                (string) $request->input('cresta')
            );
        }
        if ($request->filled('raza')) {
            $this->restrictByAve(
                $q,
                fn (Builder $sub, string $t) => $sub->where('marca_nacimiento', 'like', $t)->orWhere('placa', 'like', $t),
                (string) $request->input('raza')
            );
        }

        $items = $q->with(['ave'])->paginate(24)->withQueryString();
        $rate  = DollarRateService::getCachedRate();

        // Opciones de filtros únicos
        $colores  = $this->uniqueAveValues(['color', 'color_alternativo']);
        $crestas  = $this->uniqueAveValues(['cresta']);

        return view('marketplace.index', compact('items', 'rate', 'colores', 'crestas'));
    }

    public function show(int $id)
    {
        $pub = Publicacion::withoutGlobalScopes()
            ->where('activo', true)
            ->with(['ave'])
            ->findOrFail($id);

        $rate = DollarRateService::getCachedRate();

        // Reputación del vendedor
        $sellerRatings = MarketplaceRating::whereHas('order.publicacion', fn ($q) => $q->where('tenant_id', $pub->tenant_id))
            ->where('rated_by', 'buyer')
            ->get();
        $avgRating   = $sellerRatings->avg('score') ?? 0;
        $totalSales  = MarketplaceOrder::whereHas('publicacion', fn ($q) => $q->where('tenant_id', $pub->tenant_id))
            ->whereIn('status', ['completada'])->count();
        $totalPubs   = Publicacion::withoutGlobalScopes()->where('tenant_id', $pub->tenant_id)->where('activo', true)->count();

        // Preguntas públicas (mensajes sin datos sensibles)
        $preguntas = Mensaje::where('publicacion_id', $pub->id)->latest()->take(20)->get();

        return view('marketplace.show', compact('pub', 'rate', 'avgRating', 'totalSales', 'totalPubs', 'preguntas'));
    }

    public function question(Request $request)
    {
        $data = $request->validate([
            'publicacion_id' => ['required', 'integer', 'exists:publicaciones,id'],
            'nombre'         => ['required', 'string', 'max:255'],
            'contacto'       => ['required', 'string', 'max:255'],
            'cuerpo'         => ['required', 'string', 'max:1000'],
        ]);

        $pub = Publicacion::withoutGlobalScopes()->findOrFail($data['publicacion_id']);
        Mensaje::create([
            'tenant_id'      => $pub->tenant_id,
            'publicacion_id' => $pub->id,
            'nombre'         => $data['nombre'],
            'contacto'       => $data['contacto'],
            'cuerpo'         => $data['cuerpo'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back()->with('ok', 'Tu pregunta fue enviada al vendedor.');
    }

    public function confirmOrder(Request $request)
    {
        $data = $request->validate([
            'publicacion_id' => ['required', 'integer', 'exists:publicaciones,id'],
            'buyer_nombre'   => ['required', 'string', 'max:255'],
            'buyer_email'    => ['required', 'email', 'max:255'],
            'buyer_telefono' => ['nullable', 'string', 'max:30'],
            'buyer_pais'     => ['nullable', 'string', 'max:100'],
            'disclaimer'     => ['accepted'],
        ]);

        $pub   = Publicacion::withoutGlobalScopes()->where('activo', true)->findOrFail($data['publicacion_id']);
        $token = Str::random(48);

        $order = MarketplaceOrder::create([
            'publicacion_id' => $pub->id,
            'buyer_nombre'   => $data['buyer_nombre'],
            'buyer_email'    => $data['buyer_email'],
            'buyer_telefono' => $data['buyer_telefono'] ?? null,
            'buyer_pais'     => $data['buyer_pais'] ?? null,
            'buyer_token'    => $token,
            'precio_acordado'=> $pub->precio,
            'status'         => 'pendiente',
        ]);

        return redirect()->route('marketplace.chat', ['order' => $order->id, 'token' => $token])
            ->with('order_confirmed', true);
    }

    public function chat(Request $request, int $order)
    {
        $token = $request->query('token', '');
        $o     = MarketplaceOrder::with(['publicacion.ave', 'chats', 'ratings'])->findOrFail($order);

        if ($o->buyer_token !== $token) {
            abort(403, 'Acceso no autorizado al chat.');
        }

        $rate          = DollarRateService::getCachedRate();
        $ratingDays    = (int) config('marketplace.rating_days', 12);
        $canRate       = $o->status === 'completada' && $o->rated_by_buyer_at === null
                         && $o->created_at->diffInDays(now()) <= $ratingDays;

        return view('marketplace.chat', compact('o', 'token', 'rate', 'canRate', 'ratingDays'));
    }

    public function sendChat(Request $request, int $order)
    {
        $token = $request->input('token', '');
        $o     = MarketplaceOrder::findOrFail($order);

        if ($o->buyer_token !== $token) {
            abort(403);
        }

        $data = $request->validate([
            'mensaje'  => ['nullable', 'string', 'max:2000'],
            'adjunto'  => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,webp'],
        ]);

        $adjuntoPath  = null;
        $adjuntoNombre = null;
        if ($request->hasFile('adjunto')) {
            $file         = $request->file('adjunto');
            $adjuntoPath  = $file->store('marketplace/comprobantes', 'public');
            $adjuntoNombre = $file->getClientOriginalName();
        }

        MarketplaceChat::create([
            'order_id'       => $o->id,
            'sender_type'    => 'buyer',
            'sender_nombre'  => $o->buyer_nombre,
            'mensaje'        => $data['mensaje'] ?? null,
            'adjunto_path'   => $adjuntoPath,
            'adjunto_nombre' => $adjuntoNombre,
        ]);

        return redirect()->route('marketplace.chat', ['order' => $o->id, 'token' => $token])
            ->with('msg_sent', true);
    }

    public function rateSeller(Request $request, int $order)
    {
        $token = $request->input('token', '');
        $o     = MarketplaceOrder::findOrFail($order);

        if ($o->buyer_token !== $token) {
            abort(403);
        }

        $ratingDays = (int) config('marketplace.rating_days', 12);
        if ($o->rated_by_buyer_at || $o->created_at->diffInDays(now()) > $ratingDays) {
            return back()->with('error', 'El plazo para calificar ha vencido o ya calificaste.');
        }

        $data = $request->validate([
            'score'      => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:500'],
        ]);

        MarketplaceRating::create([
            'order_id'    => $o->id,
            'rated_by'    => 'buyer',
            'score'       => $data['score'],
            'comentario'  => $data['comentario'] ?? null,
        ]);

        $o->update(['rated_by_buyer_at' => now()]);

        // Si el vendedor también calificó → cerrar la venta
        if ($o->rated_by_seller_at) {
            $o->update(['status' => 'cerrada', 'closed_at' => now()]);
        }

        return redirect()->route('marketplace.chat', ['order' => $o->id, 'token' => $token])
            ->with('rated', true);
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function restrictByAve(Builder $q, callable $scope, string $raw): void
    {
        $term      = '%' . $raw . '%';
        $galloIds  = Gallo::withoutGlobalScopes()->where(fn (Builder $s) => $scope($s, $term))->pluck('id');
        $gallinaIds = Gallina::withoutGlobalScopes()->where(fn (Builder $s) => $scope($s, $term))->pluck('id');

        if ($galloIds->isEmpty() && $gallinaIds->isEmpty()) {
            $q->whereRaw('0 = 1');
            return;
        }

        $q->where(function (Builder $w) use ($galloIds, $gallinaIds) {
            if ($galloIds->isNotEmpty()) {
                $w->where(fn (Builder $a) => $a->where('ave_type', Gallo::class)->whereIn('ave_id', $galloIds));
            }
            if ($gallinaIds->isNotEmpty()) {
                $method = $galloIds->isNotEmpty() ? 'orWhere' : 'where';
                $w->$method(fn (Builder $b) => $b->where('ave_type', Gallina::class)->whereIn('ave_id', $gallinaIds));
            }
        });
    }

    private function uniqueAveValues(array $columns): array
    {
        $vals = collect();
        foreach ($columns as $col) {
            $vals = $vals->merge(Gallo::withoutGlobalScopes()->whereNotNull($col)->distinct()->pluck($col));
            $vals = $vals->merge(Gallina::withoutGlobalScopes()->whereNotNull($col)->distinct()->pluck($col));
        }
        return $vals->unique()->filter()->sort()->values()->toArray();
    }
}
