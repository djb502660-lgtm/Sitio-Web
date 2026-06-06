@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
<div class="flex justify-between mb-6">
    <button type="button" class="btn btn-primary btn-sm" onclick="openModal('m-promo')">Nueva promoción</button>
</div>
<div class="grid grid-2">
@foreach($promotions as $p)
<article class="panel flex gap-4" style="padding:1rem">
    <img src="{{ media_url($p['image'] ?? null) }}" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:10px">
    <div class="flex-1">
        <h3 style="font-weight:700">{{ $p['title'] }}</h3>
        <p class="text-sm text-muted mt-1">{{ $p['description'] }}</p>
        <div class="mt-3 flex gap-2">
            <button type="button" class="btn btn-outline btn-sm" onclick='editPromo(@json($p))'>Editar</button>
            <form method="post" action="{{ base_url('admin/promociones/delete') }}" data-confirm-delete style="display:inline">
                {!! ce_csrf_field() !!}<input type="hidden" name="id" value="{{ $p['id'] }}">
                <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626">Eliminar</button>
            </form>
        </div>
    </div>
</article>
@endforeach
</div>
<div id="m-promo" class="modal-backdrop">
    <div class="modal-panel">
        <h2 class="font-display" style="font-weight:700;margin-bottom:1rem">Promoción</h2>
        <form id="promo-form" method="post" action="{{ base_url('admin/promociones/store') }}" data-update-url="{{ base_url('admin/promociones/update') }}" enctype="multipart/form-data">
            {!! ce_csrf_field() !!}
            <input type="hidden" name="id" id="p-id">
            <div class="form-group"><label class="form-label">Título</label><input name="title" id="p-title" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Descripción</label><textarea name="description" id="p-desc" required class="input-field" rows="2"></textarea></div>
            <div class="form-group"><label class="form-label">Inicio</label><input name="start_date" id="p-start" type="date" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Fin</label><input name="end_date" id="p-end" type="date" required class="input-field"></div>
            <div class="form-group"><label class="form-label">URL imagen</label><input name="image_url" id="p-img" class="input-field"></div>
            <input type="file" name="image" accept="image/*" class="mb-4">
            <label class="text-sm flex gap-2 mb-4"><input type="checkbox" name="active" id="p-active" value="1" checked> Activa</label>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" data-modal-close="m-promo">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
