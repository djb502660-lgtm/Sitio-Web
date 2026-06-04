<?php /** Vista admin productos con modales */ ?>
<div class="flex flex-wrap justify-between items-center gap-4">
    <h1 class="text-2xl font-bold">Productos</h1>
    <button onclick="openModal('m-create')" class="btn-primary text-sm"><i class="fas fa-plus"></i> Nuevo</button>
</div>
<form method="get" class="mt-4 flex flex-wrap gap-2">
    <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" class="input-field max-w-xs" placeholder="Buscar">
    <select name="categoria" class="input-field max-w-xs"><option value="">Categoría</option>
        <?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (int)($_GET['categoria']??0)===$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
    </select>
    <select name="status" class="input-field max-w-xs"><option value="">Estado</option><option value="available">Disponible</option><option value="unavailable">No disponible</option></select>
    <button class="btn-primary text-sm">Filtrar</button>
</form>
<div class="mt-6 bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm"><thead class="bg-cream"><tr><th class="p-3">Img</th><th>Nombre</th><th>Cat.</th><th>Precio</th><th>Estado</th><th></th></tr></thead><tbody>
<?php foreach ($products as $p): ?>
<tr class="border-t"><td class="p-2"><img src="<?= e($p['image']) ?>" class="w-12 h-12 rounded object-cover"></td>
<td class="p-2"><?= e($p['name']) ?></td><td><?= e($p['category_name']) ?></td><td>$<?= e(number_format((float)$p['price'],2)) ?></td>
<td><?= e($p['status']) ?></td>
<td class="p-2 whitespace-nowrap">
<button type="button" class="text-gold font-semibold" onclick='editProduct(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>Editar</button>
<form method="post" action="<?= e(base_url('admin/productos/delete')) ?>" class="inline" data-confirm-delete data-confirm-text="¿Eliminar <?= e($p['name']) ?>?"><?= ce_csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="text-red-600 ml-2">Eliminar</button></form>
</td></tr>
<?php endforeach; ?>
</tbody></table>
</div>

<div id="m-create" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
<div class="bg-white rounded-2xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
<h2 class="font-bold text-lg">Nuevo producto</h2>
<form method="post" action="<?= e(base_url('admin/productos/store')) ?>" enctype="multipart/form-data" class="mt-4 space-y-3">
<?= ce_csrf_field() ?>
<input name="name" required class="input-field" placeholder="Nombre">
<select name="category_id" required class="input-field"><?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select>
<textarea name="description" required class="input-field" rows="2" placeholder="Descripción"></textarea>
<input name="price" type="number" step="0.01" required class="input-field" placeholder="Precio">
<input name="image_url" class="input-field" placeholder="URL imagen (opcional)">
<input type="file" name="image" accept="image/*" class="text-sm">
<select name="status" class="input-field"><option value="available">Disponible</option><option value="unavailable">No disponible</option></select>
<label class="text-sm"><input type="checkbox" name="featured" value="1"> Destacado</label>
<div class="flex gap-2 justify-end"><button type="button" onclick="closeModal('m-create')" class="btn-outline px-4 py-2 rounded-full border">Cancelar</button><button class="btn-primary">Guardar</button></div>
</form></div></div>

<div id="m-edit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
<div class="bg-white rounded-2xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
<h2 class="font-bold">Editar producto</h2>
<form method="post" action="<?= e(base_url('admin/productos/update')) ?>" enctype="multipart/form-data" class="mt-4 space-y-3">
<?= ce_csrf_field() ?><input type="hidden" name="id" id="e-id">
<input name="name" id="e-name" required class="input-field">
<select name="category_id" id="e-cat" required class="input-field"><?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select>
<textarea name="description" id="e-desc" required class="input-field" rows="2"></textarea>
<input name="price" id="e-price" type="number" step="0.01" required class="input-field">
<input name="image_url" id="e-img" class="input-field">
<input type="file" name="image" accept="image/*" class="text-sm">
<select name="status" id="e-status" class="input-field"><option value="available">Disponible</option><option value="unavailable">No disponible</option></select>
<label class="text-sm"><input type="checkbox" name="featured" id="e-feat" value="1"> Destacado</label>
<div class="flex gap-2 justify-end"><button type="button" onclick="closeModal('m-edit')" class="px-4 py-2 border rounded-full">Cancelar</button><button class="btn-primary">Actualizar</button></div>
</form></div></div>
<script>
function openModal(id){document.getElementById(id).classList.replace('hidden','flex');}
function closeModal(id){document.getElementById(id).classList.replace('flex','hidden');}
function editProduct(p){document.getElementById('e-id').value=p.id;document.getElementById('e-name').value=p.name;document.getElementById('e-cat').value=p.category_id;document.getElementById('e-desc').value=p.description;document.getElementById('e-price').value=p.price;document.getElementById('e-img').value=p.image||'';document.getElementById('e-status').value=p.status;document.getElementById('e-feat').checked=p.featured==1;openModal('m-edit');}
</script>
