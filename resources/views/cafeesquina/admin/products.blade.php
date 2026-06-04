@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-3">
    <p class="text-muted text-sm">{{ count($products) }} productos</p>
    <button type="button" class="btn btn-primary btn-sm" onclick="openModal('m-create')"><i class="fas fa-plus"></i> Nuevo</button>
</div>

<form method="get" class="filter-bar">
    <input type="search" name="q" value="{{ request('q', $_GET['q'] ?? '') }}" class="input-field" placeholder="Buscar...">
    <select name="categoria" class="input-field">
        <option value="">Categoría</option>
        @foreach($categories as $c)
        <option value="{{ $c['id'] }}" @selected((int) request('categoria', $_GET['categoria'] ?? 0) === (int) $c['id'])>{{ $c['name'] }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
</form>

<div class="panel panel--flush">
    <table class="data-table">
        <thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Estado</th><th class="text-right">Acciones</th></tr></thead>
        <tbody>
        @foreach($products as $p)
        <tr>
            <td><strong>{{ $p['name'] }}</strong></td>
            <td class="text-muted">{{ $p['category_name'] }}</td>
            <td>${{ number_format((float) $p['price'], 2) }}</td>
            <td>{{ $p['status'] }}</td>
            <td class="text-right">
                <button type="button" class="btn btn-outline btn-sm" onclick='editProduct(@json($p))'>Editar</button>
                <form method="post" action="{{ base_url('admin/productos/delete') }}" class="inline" data-confirm-delete style="display:inline">
                    {!! ce_csrf_field() !!}<input type="hidden" name="id" value="{{ $p['id'] }}">
                    <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div id="m-create" class="modal-backdrop" aria-hidden="true">
    <div class="modal-panel">
        <h2 class="font-display" style="font-weight:700;margin-bottom:1rem">Nuevo producto</h2>
        <form method="post" action="{{ base_url('admin/productos/store') }}" enctype="multipart/form-data">
            {!! ce_csrf_field() !!}
            <div class="form-group"><label class="form-label">Nombre</label><input name="name" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Categoría</label><select name="category_id" required class="input-field">@foreach($categories as $c)<option value="{{ $c['id'] }}">{{ $c['name'] }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Descripción</label><textarea name="description" required class="input-field" rows="2"></textarea></div>
            <div class="form-group"><label class="form-label">Precio</label><input name="price" type="number" step="0.01" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Estado</label><select name="status" class="input-field"><option value="available">Disponible</option><option value="unavailable">Agotado</option></select></div>
            <div class="form-group"><label class="form-label">URL imagen</label><input name="image_url" class="input-field"></div>
            <div class="form-group"><label class="form-label">Archivo</label><input type="file" name="image" accept="image/*"></div>
            <label class="text-sm flex gap-2 mb-4"><input type="checkbox" name="featured" value="1"> Destacado</label>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" data-modal-close="m-create">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<div id="m-edit" class="modal-backdrop" aria-hidden="true">
    <div class="modal-panel">
        <h2 class="font-display" style="font-weight:700;margin-bottom:1rem">Editar producto</h2>
        <form method="post" action="{{ base_url('admin/productos/update') }}" enctype="multipart/form-data">
            {!! ce_csrf_field() !!}
            <input type="hidden" name="id" id="e-id">
            <div class="form-group"><label class="form-label">Nombre</label><input name="name" id="e-name" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Categoría</label><select name="category_id" id="e-cat" required class="input-field">@foreach($categories as $c)<option value="{{ $c['id'] }}">{{ $c['name'] }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Descripción</label><textarea name="description" id="e-desc" required class="input-field" rows="2"></textarea></div>
            <div class="form-group"><label class="form-label">Precio</label><input name="price" id="e-price" type="number" step="0.01" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Estado</label><select name="status" id="e-status" class="input-field"><option value="available">Disponible</option><option value="unavailable">Agotado</option></select></div>
            <div class="form-group"><label class="form-label">URL imagen</label><input name="image_url" id="e-img" class="input-field"></div>
            <div class="form-group"><input type="file" name="image" accept="image/*"></div>
            <label class="text-sm flex gap-2 mb-4"><input type="checkbox" name="featured" id="e-feat" value="1"> Destacado</label>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" data-modal-close="m-edit">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
