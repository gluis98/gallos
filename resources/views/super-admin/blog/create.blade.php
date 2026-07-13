@extends('layouts.app')

@section('title', 'Nuevo Artículo')

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
        <h1 class="section-title mb-0">Nuevo artículo</h1>
        <p class="section-subtitle mb-0">Redacta y configura el SEO del artículo</p>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.blog.store') }}" id="form-blog">
@csrf
<div class="row g-4">
    {{-- Columna principal --}}
    <div class="col-lg-8">
        <div class="section-card p-4 mb-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;">Contenido</h6>

            <div class="mb-3">
                <label class="form-label fw-600 small">Título del artículo *</label>
                <input type="text" name="title" id="inp-title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title') }}" required placeholder="Ej: Vacunación completa para gallos finos">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Slug (URL)</label>
                <div class="input-group">
                    <span class="input-group-text" style="font-size:.82rem;color:#60708d;">/blog/</span>
                    <input type="text" name="slug" id="inp-slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" placeholder="generado-automaticamente">
                </div>
                @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Extracto <span class="text-muted">(resumen corto)</span></label>
                <textarea name="excerpt" rows="2" class="form-control" maxlength="500"
                    placeholder="Breve descripción del artículo que aparece en la lista del blog...">{{ old('excerpt') }}</textarea>
            </div>

            <div class="mb-1">
                <label class="form-label fw-600 small">Contenido *</label>
                <textarea name="content" id="blog-content" rows="16" class="form-control blog-editor @error('content') is-invalid @enderror"
                    required placeholder="Escribe el contenido completo del artículo aquí...">{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="text-muted small">Puedes usar formato HTML básico (&lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, etc.)</div>
        </div>

        {{-- SEO --}}
        <div class="section-card p-4">
            <h6 class="fw-700 mb-1" style="color:#1a2648;">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;color:#3b82f6;">search</span>
                SEO
            </h6>
            <p class="text-muted small mb-3">Optimiza cómo aparece este artículo en los motores de búsqueda</p>

            <div class="mb-3">
                <label class="form-label fw-600 small">Meta título <span class="text-muted">(máx. 160 caracteres)</span></label>
                <input type="text" name="meta_title" id="meta-title" class="form-control" maxlength="160"
                    value="{{ old('meta_title') }}" placeholder="Igual al título si está vacío">
                <div class="char-count"><span id="cnt-meta-title">0</span>/160</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Meta descripción <span class="text-muted">(máx. 320 caracteres)</span></label>
                <textarea name="meta_description" id="meta-desc" rows="3" class="form-control" maxlength="320"
                    placeholder="Descripción que aparece en resultados de Google...">{{ old('meta_description') }}</textarea>
                <div class="char-count"><span id="cnt-meta-desc">0</span>/320</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Palabras clave</label>
                <textarea name="keywords" rows="3" class="form-control"
                    placeholder="vacunación gallos, vacunas avícolas, historial médico gallos, crianza gallos finos...">{{ old('keywords') }}</textarea>
                <div class="text-muted small mt-1">Separadas por comas. Incluye términos relacionados con crianza de gallos.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Imagen destacada (URL)</label>
                <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image') }}"
                    placeholder="https://...">
            </div>
        </div>
    </div>

    {{-- Columna lateral --}}
    <div class="col-lg-4">
        <div class="section-card p-4 mb-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;">Publicación</h6>

            <div class="mb-3">
                <label class="form-label fw-600 small">Estado</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Fecha de publicación</label>
                <input type="datetime-local" name="published_at" class="form-control"
                    value="{{ old('published_at') }}">
                <div class="text-muted small mt-1">Deja vacío para usar la fecha actual al publicar.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small">Categoría</label>
                <select name="category" class="form-select">
                    <option value="general">General</option>
                    <option value="vacunacion">Vacunación</option>
                    <option value="historial-medico">Historial Médico</option>
                    <option value="crianza">Crianza de Gallos</option>
                    <option value="nutricion">Nutrición y Alimentación</option>
                    <option value="pedigree">Pedigree y Genética</option>
                    <option value="noticias">Noticias</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary-soft w-100">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;">save</span>
                Guardar artículo
            </button>
            <a href="{{ route('superadmin.blog.index') }}" class="btn btn-outline-soft w-100 mt-2">Cancelar</a>
        </div>

        {{-- Previsualización SEO --}}
        <div class="section-card p-4">
            <h6 class="fw-700 mb-3" style="color:#1a2648;font-size:.85rem;">
                <span class="material-symbols-outlined align-middle" style="font-size:.95rem;color:#22c55e;">preview</span>
                Vista previa en Google
            </h6>
            <div style="border:1px solid #e5e9f2;border-radius:.7rem;padding:1rem;background:#fff;">
                <div id="prev-url" style="font-size:.72rem;color:#1a73e8;margin-bottom:.2rem;">
                    {{ config('app.url') }}/blog/<span id="prev-slug">slug-del-articulo</span>
                </div>
                <div id="prev-title" style="font-size:1rem;color:#1a0dab;font-weight:600;line-height:1.3;margin-bottom:.3rem;">
                    Título del artículo
                </div>
                <div id="prev-desc" style="font-size:.82rem;color:#545454;line-height:1.5;">
                    La meta descripción aparecerá aquí...
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
function slugify(str) {
    return str.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s\-]/g, '')
        .trim().replace(/[\s\-]+/g, '-');
}

const inpTitle  = document.getElementById('inp-title');
const inpSlug   = document.getElementById('inp-slug');
const metaTitle = document.getElementById('meta-title');
const metaDesc  = document.getElementById('meta-desc');

inpTitle.addEventListener('input', function () {
    if (!inpSlug.dataset.manual) {
        inpSlug.value = slugify(this.value);
        document.getElementById('prev-slug').textContent = inpSlug.value || 'slug-del-articulo';
    }
    document.getElementById('prev-title').textContent = metaTitle.value || this.value || 'Título del artículo';
});

inpSlug.addEventListener('input', function () {
    this.dataset.manual = '1';
    document.getElementById('prev-slug').textContent = this.value || 'slug-del-articulo';
});

metaTitle.addEventListener('input', function () {
    const cnt = document.getElementById('cnt-meta-title');
    cnt.textContent = this.value.length;
    cnt.closest('.char-count').classList.toggle('over', this.value.length > 160);
    document.getElementById('prev-title').textContent = this.value || inpTitle.value || 'Título del artículo';
});

metaDesc.addEventListener('input', function () {
    const cnt = document.getElementById('cnt-meta-desc');
    cnt.textContent = this.value.length;
    cnt.closest('.char-count').classList.toggle('over', this.value.length > 320);
    const preview = this.value.substring(0, 160) + (this.value.length > 160 ? '...' : '');
    document.getElementById('prev-desc').textContent = preview || 'La meta descripción aparecerá aquí...';
});
</script>
@endsection
