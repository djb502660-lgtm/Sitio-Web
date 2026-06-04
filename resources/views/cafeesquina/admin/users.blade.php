@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
<div class="panel panel--flush">
    <table class="data-table">
        <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th class="text-right">Acciones</th></tr></thead>
        <tbody>
        @foreach($users as $u)
        <tr>
            <td>{{ $u['username'] }}</td>
            <td class="text-muted">{{ $u['email'] }}</td>
            <td>{{ $u['role'] }}</td>
            <td class="text-right">
                <button type="button" class="btn btn-outline btn-sm" onclick='editUser(@json($u))'>Editar</button>
                @if($u['role'] !== 'admin')
                <form method="post" action="{{ base_url('admin/usuarios/delete') }}" data-confirm-delete style="display:inline">
                    {!! ce_csrf_field() !!}<input type="hidden" name="id" value="{{ $u['id'] }}">
                    <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626">Eliminar</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div id="m-user" class="modal-backdrop">
    <div class="modal-panel">
        <h2 class="font-display" style="font-weight:700;margin-bottom:1rem">Editar usuario</h2>
        <form method="post" action="{{ base_url('admin/usuarios/update') }}">
            {!! ce_csrf_field() !!}
            <input type="hidden" name="id" id="u-id">
            <div class="form-group"><label class="form-label">Usuario</label><input name="username" id="u-user" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Email</label><input name="email" id="u-email" required class="input-field"></div>
            <div class="form-group"><label class="form-label">Nombre</label><input name="full_name" id="u-name" class="input-field"></div>
            <div class="form-group"><label class="form-label">Teléfono</label><input name="phone" id="u-phone" class="input-field"></div>
            <div class="form-group"><label class="form-label">Rol</label><select name="role" id="u-role" class="input-field"><option value="client">Cliente</option><option value="admin">Admin</option></select></div>
            <button type="submit" class="btn btn-primary btn-block">Guardar</button>
        </form>
    </div>
</div>
@endsection
