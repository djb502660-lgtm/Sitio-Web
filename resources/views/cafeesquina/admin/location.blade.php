@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
<p class="text-muted text-sm mb-6">Estos datos se muestran en la sección <strong>Ubicación</strong> del inicio.</p>
<div class="panel" style="max-width:42rem">
    <form method="post" action="{{ base_url('admin/ubicacion/actualizar') }}">
        {!! ce_csrf_field() !!}
        <div class="form-group">
            <label class="form-label" for="loc-address">Dirección</label>
            <input type="text" name="address" id="loc-address" class="input-field" required maxlength="255" value="{{ $settings['address'] }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="loc-hours">Horario</label>
            <input type="text" name="hours" id="loc-hours" class="input-field" required maxlength="255" value="{{ $settings['hours'] }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="loc-map">URL del mapa (iframe de Google Maps)</label>
            <input type="url" name="map_embed" id="loc-map" class="input-field" required value="{{ $settings['map_embed'] }}">
            <p class="text-muted text-sm mt-2">En Google Maps: Compartir → Insertar un mapa → copia la URL del <code>src</code> del iframe.</p>
        </div>
        <div class="flex gap-2 justify-between mt-6">
            <a href="{{ base_url('') }}#ubicacion" class="btn btn-outline" target="_blank" rel="noopener">Ver en el sitio</a>
            <button type="submit" class="btn btn-primary">Guardar ubicación</button>
        </div>
    </form>
</div>
@endsection
