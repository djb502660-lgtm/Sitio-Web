<h1 class="text-2xl font-bold">Usuarios</h1>
<div class="mt-6 bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm"><thead class="bg-cream"><tr><th class="p-3 text-left">Usuario</th><th>Email</th><th>Rol</th><th></th></tr></thead><tbody>
<?php foreach ($users as $u): ?>
<tr class="border-t"><td class="p-3"><?= e($u['username']) ?></td><td class="p-3"><?= e($u['email']) ?></td><td><?= e($u['role']) ?></td>
<td class="p-3"><button class="text-gold" onclick='editUser(<?= json_encode($u, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>Editar</button>
<?php if ($u['role'] !== 'admin'): ?>
<form method="post" action="<?= e(base_url('admin/usuarios/delete')) ?>" class="inline" data-confirm-delete><?= ce_csrf_field() ?><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="text-red-600 ml-2">Eliminar</button></form>
<?php endif; ?></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<div id="m-user" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
<div class="bg-white rounded-2xl p-6 max-w-md w-full"><h2 class="font-bold">Editar usuario</h2>
<form method="post" action="<?= e(base_url('admin/usuarios/update')) ?>" class="mt-4 space-y-3"><?= ce_csrf_field() ?>
<input type="hidden" name="id" id="u-id">
<input name="username" id="u-user" required class="input-field">
<input name="email" id="u-email" required class="input-field">
<input name="full_name" id="u-name" class="input-field">
<input name="phone" id="u-phone" class="input-field">
<select name="role" id="u-role" class="input-field"><option value="client">Cliente</option><option value="admin">Admin</option></select>
<button class="btn-primary w-full">Guardar</button></form></div></div>
<script>
function openModal(id){document.getElementById(id).classList.replace('hidden','flex');}
function editUser(u){document.getElementById('u-id').value=u.id;document.getElementById('u-user').value=u.username;document.getElementById('u-email').value=u.email;document.getElementById('u-name').value=u.full_name||'';document.getElementById('u-phone').value=u.phone||'';document.getElementById('u-role').value=u.role;openModal('m-user');}
</script>
