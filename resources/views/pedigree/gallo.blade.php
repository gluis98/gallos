@extends('layouts.app')

@section('content')
<section class="container-fluid bg-white p-4 text-dark">
    <h2 class="h4 mb-2">Árbol genealógico — Gallo #{{ $id }}</h2>
    <p class="text-muted small mb-3">Ancestros arriba · Sujeto al centro · Descendencia abajo</p>
    <div id="pedigree-tree" class="border rounded p-2 bg-light overflow-auto" style="min-height: 280px;"></div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const id = @json($id);
    const el = document.getElementById('pedigree-tree');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    await ModuleLoader.run('#pedigree-tree', async () => {
        const res = await fetch('/api/gallos/' + id + '/pedigree', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token || '' },
        });
        if (!res.ok) {
            el.innerHTML = '<p class="text-danger mb-0">No se pudo cargar el pedigree.</p>';
            return;
        }
        const payload = await res.json();
        if (!window.PedigreeTree) {
            el.innerHTML = '<p class="text-danger mb-0">Módulo de pedigree no disponible.</p>';
            return;
        }
        el.innerHTML = '';
        PedigreeTree.render(payload.data, el, { consanguinidad: payload.consanguinidad });
    }, { message: 'Cargando árbol genealógico...', minHeight: '280px' });
});
</script>
@endsection
