@extends('layouts.app')

@section('title', 'Gestión de Blog')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="section-title mb-0">
            <span class="material-symbols-outlined align-middle me-1" style="font-size:1.6rem;color:#3b82f6;">article</span>
            Blog
        </h1>
        <p class="section-subtitle">Gestiona los artículos publicados en el blog público</p>
    </div>
    <a href="{{ route('superadmin.blog.create') }}" class="btn btn-primary-soft px-4">
        <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;">add</span>
        Nuevo artículo
    </a>
</div>

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-3 mb-3" style="background:#f0fdf4;color:#166534;">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="section-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.88rem;">
            <thead style="background:#f8faff;border-bottom:1px solid #e5e9f2;">
                <tr>
                    <th class="px-4 py-3 fw-700 text-muted" style="width:40%">Título</th>
                    <th class="px-3 py-3 fw-700 text-muted">Categoría</th>
                    <th class="px-3 py-3 fw-700 text-muted">Estado</th>
                    <th class="px-3 py-3 fw-700 text-muted">Publicado</th>
                    <th class="px-3 py-3 fw-700 text-muted text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-600" style="color:#1a2648;">{{ $post->title }}</div>
                    <div style="font-size:.78rem;color:#94a3b8;">/blog/{{ $post->slug }}</div>
                </td>
                <td class="px-3 py-3">
                    <span style="background:#eff6ff;color:#2563eb;border-radius:.5rem;padding:.2rem .65rem;font-size:.75rem;font-weight:600;">
                        {{ ucfirst($post->category) }}
                    </span>
                </td>
                <td class="px-3 py-3">
                    @if($post->status === 'published')
                        <span style="background:#f0fdf4;color:#166534;border-radius:.5rem;padding:.2rem .65rem;font-size:.75rem;font-weight:700;">
                            <span class="material-symbols-outlined align-middle" style="font-size:.8rem;">check_circle</span> Publicado
                        </span>
                    @else
                        <span style="background:#fefce8;color:#854d0e;border-radius:.5rem;padding:.2rem .65rem;font-size:.75rem;font-weight:700;">
                            <span class="material-symbols-outlined align-middle" style="font-size:.8rem;">edit_note</span> Borrador
                        </span>
                    @endif
                </td>
                <td class="px-3 py-3" style="color:#60708d;font-size:.82rem;">
                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : '—' }}
                </td>
                <td class="px-3 py-3 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        @if($post->isPublished())
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-outline-soft" title="Ver en blog">
                            <span class="material-symbols-outlined" style="font-size:.9rem;">open_in_new</span>
                        </a>
                        @endif
                        <a href="{{ route('superadmin.blog.edit', $post) }}" class="btn btn-sm btn-outline-soft" title="Editar">
                            <span class="material-symbols-outlined" style="font-size:.9rem;">edit</span>
                        </a>
                        <form method="POST" action="{{ route('superadmin.blog.destroy', $post) }}" onsubmit="return confirm('¿Eliminar este artículo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="border:1px solid #fee2e2;color:#ef4444;background:#fff;border-radius:.7rem;" title="Eliminar">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5" style="color:#94a3b8;">
                    <span class="material-symbols-outlined d-block mb-2" style="font-size:2.5rem;">article</span>
                    No hay artículos aún. <a href="{{ route('superadmin.blog.create') }}">Crear el primero</a>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
    <div class="px-4 py-3 border-top" style="background:#fafbfe;">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
