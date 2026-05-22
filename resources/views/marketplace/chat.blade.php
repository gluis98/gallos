@php
    $ave      = $o->publicacion?->ave;
    $isGallo  = $o->publicacion?->ave_type === \App\Models\Gallo::class;
    $whatsapp = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
    $ratingDays = (int) config('marketplace.rating_days', 12);
    $statusLabels = [
        'pendiente'       => ['Pendiente de contacto','#f59e0b'],
        'en_negociacion'  => ['En negociación','#3b82f6'],
        'completada'      => ['Completada','#16a34a'],
        'cancelada'       => ['Cancelada','#ef4444'],
        'cerrada'         => ['Cerrada y calificada','#8b5cf6'],
    ];
    [$statusLabel, $statusColor] = $statusLabels[$o->status] ?? ['Desconocido','#60708d'];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat de compra — Galpon</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#1a2648">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,::before,::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',sans-serif;background:#f4f7fb;color:#13203a;min-height:100vh;}
        .shell{max-width:760px;margin:0 auto;padding:1.5rem 1rem 4rem;}

        /* Nav */
        nav{background:linear-gradient(165deg,rgba(26,38,72,.97),rgba(22,28,52,.97));padding:.85rem 1.25rem;display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;border-radius:0 0 1rem 1rem;}
        nav a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.85rem;transition:color .2s;}
        nav a:hover{color:#fff;}
        nav .brand{font-weight:800;color:#fff;font-size:1rem;}

        /* Order info card */
        .order-card{background:#fff;border:1px solid #e5e9f2;border-radius:1.1rem;padding:1.25rem 1.4rem;margin-bottom:1.25rem;box-shadow:0 2px 12px rgba(16,39,77,.05);}
        .order-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.75rem;margin-bottom:.85rem;}
        .order-title{font-size:1.05rem;font-weight:800;color:#0f1830;}
        .status-chip{border-radius:999px;padding:.3rem .85rem;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;}
        .order-meta{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.5rem .85rem;}
        .meta-field label{display:block;font-size:.65rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#60708d;margin-bottom:.1rem;}
        .meta-field span{font-size:.88rem;font-weight:600;color:#1a2648;}
        .order-id-note{background:#f0f6ff;border:1px solid #c7d6f0;border-radius:.7rem;padding:.6rem .85rem;font-size:.78rem;color:#1a5276;margin-top:.85rem;display:flex;align-items:center;gap:.4rem;}

        /* Disclaimer */
        .disclaimer{background:#fff8e6;border:1px solid rgba(245,158,11,.3);border-radius:.8rem;padding:.85rem 1rem;margin-bottom:1.25rem;font-size:.8rem;color:#92400e;line-height:1.65;display:flex;align-items:flex-start;gap:.5rem;}

        /* Chat */
        .chat-box{background:#fff;border:1px solid #e5e9f2;border-radius:1.1rem;overflow:hidden;margin-bottom:1.25rem;box-shadow:0 2px 12px rgba(16,39,77,.05);}
        .chat-header{background:linear-gradient(135deg,#1a2648,#2d4278);padding:.85rem 1.25rem;display:flex;align-items:center;gap:.5rem;}
        .chat-header span{color:#fff;font-weight:700;font-size:.9rem;}
        .chat-messages{padding:1rem;min-height:200px;max-height:420px;overflow-y:auto;display:flex;flex-direction:column;gap:.75rem;}
        .msg{display:flex;flex-direction:column;max-width:80%;}
        .msg.buyer{align-self:flex-end;align-items:flex-end;}
        .msg.seller{align-self:flex-start;align-items:flex-start;}
        .msg-bubble{padding:.65rem .9rem;border-radius:.9rem;font-size:.87rem;line-height:1.6;}
        .msg.buyer .msg-bubble{background:linear-gradient(135deg,#3b82f6,#6d5efc);color:#fff;border-radius:.9rem .9rem 0 .9rem;}
        .msg.seller .msg-bubble{background:#f0f6ff;color:#1a2648;border:1px solid #dce5f3;border-radius:.9rem .9rem .9rem 0;}
        .msg-meta{font-size:.65rem;color:#94a3b8;margin-top:.2rem;}
        .msg-attachment{display:flex;align-items:center;gap:.4rem;font-size:.78rem;padding:.4rem .65rem;border-radius:.6rem;margin-top:.3rem;text-decoration:none;}
        .msg.buyer .msg-attachment{background:rgba(255,255,255,.2);color:#fff;}
        .msg.seller .msg-attachment{background:#e8f0fe;color:#1a5276;}
        .chat-empty{text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem;}
        .chat-empty .icon{font-size:2.5rem;display:block;margin-bottom:.5rem;opacity:.4;}

        /* Form */
        .chat-form{border-top:1px solid #f1f5fb;padding:1rem 1.25rem;background:#fafbff;}
        .chat-form-row{display:flex;gap:.65rem;align-items:flex-end;}
        .chat-textarea{flex:1;border:1.5px solid #dce5f3;border-radius:.75rem;padding:.65rem .85rem;font-family:'Poppins',sans-serif;font-size:.88rem;color:#13203a;outline:none;resize:none;transition:border-color .2s;height:70px;background:#fff;}
        .chat-textarea:focus{border-color:#6ea4ff;}
        .btn-send{height:42px;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.75rem;padding:0 1.1rem;font-family:'Poppins',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;transition:opacity .2s;white-space:nowrap;}
        .btn-send:hover{opacity:.9;}
        .file-label{display:flex;align-items:center;gap:.3rem;height:42px;background:#f3f6fd;border:1.5px solid #dce5f3;border-radius:.75rem;padding:0 .85rem;font-size:.82rem;color:#374151;cursor:pointer;transition:all .2s;white-space:nowrap;}
        .file-label:hover{background:#e8f0fe;border-color:#93c5fd;color:#1a2648;}
        .file-label input{display:none;}
        .file-name{font-size:.72rem;color:#60708d;margin-top:.35rem;}
        .send-note{font-size:.7rem;color:#94a3b8;margin-top:.4rem;}

        /* Rating */
        .rating-box{background:#fff;border:1px solid #e5e9f2;border-radius:1.1rem;padding:1.4rem 1.5rem;box-shadow:0 2px 12px rgba(16,39,77,.05);}
        .rating-box h3{font-size:1rem;font-weight:800;color:#0f1830;margin-bottom:.25rem;}
        .rating-box p{font-size:.82rem;color:#60708d;margin-bottom:1rem;line-height:1.6;}
        .stars-input{display:flex;gap:.35rem;margin-bottom:1rem;}
        .star-btn{background:none;border:none;cursor:pointer;font-size:1.8rem;color:#d1d5db;transition:color .2s;padding:0;}
        .star-btn:hover, .star-btn.active{color:#f59e0b;}
        .rating-textarea{width:100%;border:1.5px solid #dce5f3;border-radius:.7rem;padding:.65rem .85rem;font-family:'Poppins',sans-serif;font-size:.88rem;color:#13203a;outline:none;resize:vertical;transition:border-color .2s;height:80px;margin-bottom:.85rem;}
        .rating-textarea:focus{border-color:#6ea4ff;}
        .btn-rate{background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:.75rem;padding:.7rem 1.5rem;font-family:'Poppins',sans-serif;font-size:.9rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
        .btn-rate:hover{opacity:.9;}
        .rated-badge{display:flex;align-items:center;gap:.5rem;background:#dcfce7;border:1px solid #86efac;border-radius:.8rem;padding:.75rem 1rem;color:#166534;font-size:.88rem;font-weight:600;}
        .days-notice{font-size:.75rem;color:#f59e0b;font-weight:600;margin-top:.5rem;}

        /* Alerts */
        .alert-success{background:#dcfce7;border:1px solid #86efac;color:#166534;border-radius:.65rem;padding:.65rem 1rem;font-size:.88rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:.4rem;}
    </style>
</head>
<body>

<nav>
    <a href="{{ route('marketplace.index') }}" style="display:flex;align-items:center;gap:.35rem;">
        <img src="{{ asset('img/logo.png') }}" alt="Galpon" style="width:28px;height:28px;border-radius:7px;object-fit:cover;">
        <span class="brand">Galpon</span>
    </a>
    <span style="color:rgba(255,255,255,.35);margin:0 .25rem;">/</span>
    <a href="{{ route('marketplace.index') }}">Marketplace</a>
    <span style="color:rgba(255,255,255,.35);margin:0 .25rem;">/</span>
    <span style="color:#fff;">Chat #{{ $o->id }}</span>
</nav>

<div class="shell">

    @if(session('order_confirmed'))
        <div class="alert-success">
            <span class="material-symbols-outlined" style="font-size:1.1rem;">check_circle</span>
            ¡Interés de compra confirmado! El vendedor ha sido notificado. Usa este chat para coordinar los detalles.
        </div>
    @endif
    @if(session('msg_sent'))
        <div class="alert-success">
            <span class="material-symbols-outlined" style="font-size:1.1rem;">send</span>
            Mensaje enviado correctamente.
        </div>
    @endif
    @if(session('rated'))
        <div class="alert-success">
            <span class="material-symbols-outlined" style="font-size:1.1rem;">star</span>
            ¡Gracias por tu calificación! La venta se cerrará cuando el vendedor también califique.
        </div>
    @endif

    {{-- Info de la orden --}}
    <div class="order-card">
        <div class="order-header">
            <div class="order-title">
                {{ $isGallo ? '🐓 Gallo' : '🐔 Gallina' }}: {{ $ave?->nombre ?? 'Ave publicada' }}
            </div>
            <span class="status-chip" style="background:{{ $statusColor }}22;color:{{ $statusColor }};border:1px solid {{ $statusColor }}44;">
                {{ $statusLabel }}
            </span>
        </div>
        <div class="order-meta">
            <div class="meta-field"><label>Comprador</label><span>{{ $o->buyer_nombre }}</span></div>
            <div class="meta-field"><label>Email</label><span>{{ $o->buyer_email }}</span></div>
            @if($o->buyer_telefono)
            <div class="meta-field"><label>Teléfono</label><span>{{ $o->buyer_telefono }}</span></div>
            @endif
            @if($o->buyer_pais)
            <div class="meta-field"><label>País</label><span>{{ $o->buyer_pais }}</span></div>
            @endif
            <div class="meta-field"><label>Precio pactado</label><span>${{ number_format($o->precio_acordado ?? 0, 2) }}@if($rate > 0) ≈ Bs. {{ number_format(($o->precio_acordado ?? 0) * $rate, 2) }}@endif</span></div>
            <div class="meta-field"><label>Fecha</label><span>{{ $o->created_at->format('d/m/Y H:i') }}</span></div>
        </div>
        <div class="order-id-note">
            <span class="material-symbols-outlined" style="font-size:1rem;">info</span>
            Orden #{{ $o->id }} — Guarda este enlace para acceder al chat en cualquier momento.
        </div>
    </div>

    {{-- Disclaimer --}}
    <div class="disclaimer">
        <span class="material-symbols-outlined" style="font-size:1rem;flex-shrink:0;margin-top:.05rem;">warning</span>
        <div>
            <strong>Recordatorio:</strong> Galpon no interviene ni garantiza esta transacción. Eres responsable de verificar al vendedor, acordar los términos del pago y la entrega directamente. Esta compra ha quedado registrada como constancia en el sistema.
        </div>
    </div>

    {{-- Chat --}}
    <div class="chat-box">
        <div class="chat-header">
            <span class="material-symbols-outlined" style="font-size:1rem;color:rgba(255,255,255,.7);">forum</span>
            <span>Chat con el vendedor</span>
        </div>

        <div class="chat-messages" id="chat-messages">
            @if($o->chats->isEmpty())
                <div class="chat-empty">
                    <span class="icon material-symbols-outlined">forum</span>
                    <div>Aún no hay mensajes. ¡Inicia la conversación!</div>
                    <div style="font-size:.75rem;margin-top:.3rem;color:#bfcfe7;">Envía un mensaje al vendedor para coordinar el pago y la entrega.</div>
                </div>
            @else
                @foreach($o->chats as $msg)
                <div class="msg {{ $msg->sender_type }}">
                    <div class="msg-bubble">
                        @if($msg->mensaje){{ $msg->mensaje }}@endif
                        @if($msg->adjunto_path)
                            <a href="{{ asset('storage/' . $msg->adjunto_path) }}" target="_blank" class="msg-attachment">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">attach_file</span>
                                {{ $msg->adjunto_nombre ?? 'Ver adjunto' }}
                            </a>
                        @endif
                    </div>
                    <div class="msg-meta">{{ ucfirst($msg->sender_type === 'buyer' ? 'Tú' : 'Vendedor') }} · {{ $msg->created_at->format('d/m H:i') }}</div>
                </div>
                @endforeach
            @endif
        </div>

        @if(!$o->isClosed())
        <div class="chat-form">
            <form method="POST" action="{{ route('marketplace.chat.send', $o->id) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div style="margin-bottom:.5rem;">
                    <textarea name="mensaje" class="chat-textarea" placeholder="Escribe tu mensaje al vendedor..." maxlength="2000"></textarea>
                </div>
                <div class="chat-form-row">
                    <label class="file-label">
                        <span class="material-symbols-outlined" style="font-size:.9rem;">attach_file</span>
                        Adjuntar comprobante
                        <input type="file" name="adjunto" accept=".jpg,.jpeg,.png,.pdf,.webp" onchange="document.getElementById('fname').textContent=this.files[0]?.name||''">
                    </label>
                    <button type="submit" class="btn-send">
                        <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:middle;">send</span>
                        Enviar
                    </button>
                </div>
                <div id="fname" class="file-name"></div>
                <div class="send-note">Puedes adjuntar fotos de comprobantes de pago (JPG, PNG, PDF). Máx. 5 MB.</div>
            </form>
        </div>
        @else
        <div style="text-align:center;padding:1.25rem;color:#60708d;font-size:.85rem;">
            <span class="material-symbols-outlined" style="font-size:1.5rem;display:block;margin-bottom:.4rem;color:#8b5cf6;">lock</span>
            Esta venta está cerrada y calificada.
        </div>
        @endif
    </div>

    {{-- Calificación --}}
    @if($o->status === 'completada')
        @if($o->rated_by_buyer_at)
            <div class="rating-box">
                <div class="rated-badge">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">star</span>
                    Ya calificaste al vendedor. La venta se cerrará cuando el vendedor también califique.
                </div>
            </div>
        @elseif($canRate)
            <div class="rating-box">
                <h3>⭐ Califica al vendedor</h3>
                <p>Tienes <strong>{{ $ratingDays }} días</strong> para calificar la experiencia de compra.
                   Tu calificación es importante para la comunidad de criadores.</p>
                <form method="POST" action="{{ route('marketplace.rate', $o->id) }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="score" id="score-val" required>
                    <div class="stars-input" id="stars-input">
                        @for($s=1;$s<=5;$s++)
                            <button type="button" class="star-btn" data-score="{{ $s }}" onclick="setScore({{ $s }})">★</button>
                        @endfor
                    </div>
                    <textarea name="comentario" class="rating-textarea" placeholder="Escribe un comentario sobre la experiencia (opcional)..." maxlength="500"></textarea>
                    <button type="submit" class="btn-rate">
                        <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;">star</span>
                        Enviar calificación
                    </button>
                    <div class="days-notice">
                        ⏳ Tienes {{ $ratingDays - $o->created_at->diffInDays(now()) }} día(s) restantes para calificar.
                    </div>
                </form>
            </div>
        @else
            <div class="rating-box">
                <div style="text-align:center;padding:.5rem;color:#94a3b8;font-size:.85rem;">
                    <span class="material-symbols-outlined" style="display:block;font-size:2rem;opacity:.4;margin-bottom:.3rem;">schedule</span>
                    El plazo para calificar al vendedor ha vencido.
                </div>
            </div>
        @endif
    @endif

</div>

<script>
function setScore(s) {
    document.getElementById('score-val').value = s;
    document.querySelectorAll('.star-btn').forEach((b,i) => b.classList.toggle('active', i < s));
}
// Auto scroll al final del chat
const msgs = document.getElementById('chat-messages');
if (msgs) msgs.scrollTop = msgs.scrollHeight;
</script>
</body>
</html>
