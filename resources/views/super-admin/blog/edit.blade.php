@extends('layouts.app')

@section('title', 'Editar: ' . $post->title)

@section('styles')
<style>
    .blog-editor { min-height: 320px; font-family: 'Poppins', sans-serif; }
    .char-count { font-size: .74rem; color: #94a3b8; text-align: right; margin-top: .25rem; }
    .char-count.over { color: #ef4444; }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('superadmin.blog.index') }}" class="btn btn-sm btn-outline-soft">
        <span class="material-symbols-outlined align-middle" style="font-size:1rem;">arrow_back</span>
    </a>
    <div>
        <h1 class="section-title mb-0">Editar artículo</h1>
        <p class="section-subtitle mb-0">{{ $post->title }}</p>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.blog.update', $post) }}" id="form-blog">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="section-card p-4 mb-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;">Contenido</h6>

            <div class="mb-3">
                <label class="form-label fw-600 small">Título del artículo *</label>
                <input type="text" name="title" id="inp-title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $post->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Slug (URL)</label>
                <div class="input-group">
                    <span class="input-group-text" style="font-size:.82rem;color:#60708d;">/blog/</span>
                    <input type="text" name="slug" id="inp-slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $post->slug) }}" data-manual="1">
                </div>
                @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Extracto</label>
                <textarea name="excerpt" rows="2" class="form-control" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="mb-1">
                <label class="form-label fw-600 small">Contenido *</label>
                <textarea name="content" rows="16" class="form-control blog-editor @error('content') is-invalid @enderror"
                    required>{{ old('content', $post->content) }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="section-card p-4">
            <h6 class="fw-700 mb-1" style="color:#1a2648;">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;color:#3b82f6;">search</span>
                SEO
            </h6>
            <p class="text-muted small mb-3">Optimiza cómo aparece este artículo en los motores de búsqueda</p>

            <div class="mb-3">
                <label class="form-label fw-600 small">Meta título <span class="text-muted">(máx. 160 caracteres)</span></label>
                <input type="text" name="meta_title" id="meta-title" class="form-control" maxlength="160"
                    value="{{ old('meta_title', $post->meta_title) }}">
                <div class="char-count"><span id="cnt-meta-title">{{ strlen(old('meta_title', $post->meta_title ?? '')) }}</span>/160</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Meta descripción <span class="text-muted">(máx. 320 caracteres)</span></label>
                <textarea name="meta_description" id="meta-desc" rows="3" class="form-control"
                    maxlength="320">{{ old('meta_description', $post->meta_description) }}</textarea>
                <div class="char-count"><span id="cnt-meta-desc">{{ strlen(old('meta_description', $post->meta_description ?? '')) }}</span>/320</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Palabras clave</label>
                <textarea name="keywords" rows="3" class="form-control">{{ old('keywords', $post->keywords) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Imagen destacada (URL)</label>
                <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image', $post->featured_image) }}">
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="section-card p-4 mb-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;">Publicación</h6>

            <div class="mb-3">
                <label class="form-label fw-600 small">Estado</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Fecha de publicación</label>
                <input type="datetime-local" name="published_at" class="form-control"
                    value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Categoría</label>
                <select name="category" class="form-select">
                    @foreach(['general' => 'General', 'vacunacion' => 'Vacunación', 'historial-medico' => 'Historial Médico', 'crianza' => 'Crianza de Gallos', 'nutricion' => 'Nutrición y Alimentación', 'pedigree' => 'Pedigree y Genética', 'noticias' => 'Noticias'] as $val => $label)
                    <option value="{{ $val }}" {{ old('category', $post->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary-soft w-100">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;">save</span>
                Guardar cambios
            </button>

            @if($post->isPublished())
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-outline-soft w-100 mt-2">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:.9rem;">open_in_new</span>
                Ver artículo
            </a>
            @endif

            <a href="{{ route('superadmin.blog.index') }}" class="btn btn-outline-soft w-100 mt-2">Cancelar</a>
        </div>

        <div class="section-card p-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;font-size:.85rem;">
                <span class="material-symbols-outlined align-middle" style="font-size:.95rem;color:#22c55e;">preview</span>
                Vista previa en Google
            </h6>
            <div style="border:1px solid #e5e9f2;border-radius:.7rem;padding:1rem;background:#fff;">
                <div style="font-size:.72rem;color:#1a73e8;margin-bottom:.2rem;">
                    {{ config('app.url') }}/blog/{{ $post->slug }}
                </div>
                <div id="prev-title" style="font-size:1rem;color:#1a0dab;font-weight:600;line-height:1.3;margin-bottom:.3rem;">
                    {{ $post->meta_title ?: $post->title }}
                </div>
                <div id="prev-desc" style="font-size:.82rem;color:#545454;line-height:1.5;">
                    {{ $post->meta_description ?: 'Sin meta descripción' }}
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
const metaTitle = document.getElementById('meta-title');
const metaDesc  = document.getElementById('meta-desc');

metaTitle.addEventListener('input', function () {
    const cnt = document.getElementById('cnt-meta-title');
    cnt.textContent = this.value.length;
    cnt.closest('.char-count').classList.toggle('over', this.value.length > 160);
    document.getElementById('prev-title').textContent = this.value || document.getElementById('inp-title').value;
});

metaDesc.addEventListener('input', function () {
    const cnt = document.getElementById('cnt-meta-desc');
    cnt.textContent = this.value.length;
    cnt.closest('.char-count').classList.toggle('over', this.value.length > 320);
    const preview = this.value.substring(0, 160) + (this.value.length > 160 ? '...' : '');
    document.getElementById('prev-desc').textContent = preview || 'Sin meta descripción';
});
</script>
@endsection
