@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
<div class="flex justify-between mb-6">
    <p class="text-muted text-sm">{{ count($categories) }} categorías</p>
    <button type="button" class="btn btn-primary btn-sm" onclick="resetCatForm();openModal('m-cat')">Nueva</button>
</div>
<div class="panel panel--flush">
    <table class="data-table">
        <thead><tr><th>Nombre</th><th>Descripción</th><th class="text-right">Acciones</th></tr></thead>
        <tbody>
        @foreach($categories as $c)
        <tr>
            <td><strong>{{ $c['name'] }}</strong></td>
            <td class="text-muted">{{ $c['description'] ?? '—' }}</td>
            <td class="text-right">
                <button type="button" class="btn btn-outline btn-sm" onclick='editCat(@json($c))'>Editar</button>
                <form method="post" action="{{ base_url('admin/categorias/delete') }}" class="inline" data-confirm-delete style="display:inline">
                    {!! ce_csrf_field() !!}<input type="hidden" name="id" value="{{ $c['id'] }}">
                    <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div id="m-cat" class="modal-backdrop">
    <div class="modal-panel">
        <h2 id="cat-title" class="font-display" style="font-weight:700;margin-bottom:1rem">Nueva categoría</h2>
        <form id="cat-form" method="post" action="{{ base_url('admin/categorias/store') }}" data-store-url="{{ base_url('admin/categorias/store') }}" data-update-url="{{ base_url('admin/categorias/update') }}">
            {!! ce_csrf_field() !!}
            <input type="hidden" name="id" id="c-id">
            <div class="form-group"><label class="form-label">Nombre</label><input name="name" id="c-name" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Descripción</label><input name="description" id="c-desc" class="input-field"></div>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" data-modal-close="m-cat">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>function resetCatForm(){const f=document.getElementById('cat-form');document.getElementById('cat-title').textContent='Nueva categoría';f.action=f.dataset.storeUrl;document.getElementById('c-id').value='';}</script>
@endpush
