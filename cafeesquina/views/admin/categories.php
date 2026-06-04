<div class="flex justify-between items-center"><h1 class="text-2xl font-bold">Categorías</h1>
<button onclick="openModal('m-cat')" class="btn-primary text-sm"><i class="fas fa-plus"></i> Nueva</button></div>
<div class="mt-6 bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm"><thead class="bg-cream"><tr><th class="p-3 text-left">Nombre</th><th>Descripción</th><th></th></tr></thead><tbody>
<?php foreach ($categories as $c): ?>
<tr class="border-t"><td class="p-3 font-medium"><?= e($c['name']) ?></td><td class="p-3"><?= e($c['description'] ?? '') ?></td>
<td class="p-3"><button class="text-gold" onclick='editCat(<?= json_encode($c, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>Editar</button>
<form method="post" action="<?= e(base_url('admin/categorias/delete')) ?>" class="inline" data-confirm-delete><?= ce_csrf_field() ?><input type="hidden" name="id" value="<?= (int)$c['id'] ?>"><button class="text-red-600 ml-2">Eliminar</button></form></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<div id="m-cat" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
<div class="bg-white rounded-2xl p-6 max-w-md w-full"><h2 id="cat-title" class="font-bold">Nueva categoría</h2>
<form id="cat-form" method="post" action="<?= e(base_url('admin/categorias/store')) ?>" class="mt-4 space-y-3"><?= ce_csrf_field() ?>
<input type="hidden" name="id" id="c-id">
<input name="name" id="c-name" required class="input-field" placeholder="Nombre">
<input name="description" id="c-desc" class="input-field" placeholder="Descripción">
<div class="flex gap-2 justify-end"><button type="button" onclick="closeModal('m-cat')" class="px-4 py-2 border rounded-full">Cancelar</button><button class="btn-primary">Guardar</button></div>
</form></div></div>
<script>
function openModal(id){document.getElementById(id).classList.replace('hidden','flex');}
function closeModal(id){document.getElementById(id).classList.replace('flex','hidden');}
function editCat(c){document.getElementById('cat-title').textContent='Editar categoría';document.getElementById('cat-form').action='<?= e(base_url('admin/categorias/update')) ?>';document.getElementById('c-id').value=c.id;document.getElementById('c-name').value=c.name;document.getElementById('c-desc').value=c.description||'';openModal('m-cat');}
document.querySelector('[onclick*="m-cat"]')?.addEventListener?.('click',()=>{document.getElementById('cat-title').textContent='Nueva categoría';document.getElementById('cat-form').action='<?= e(base_url('admin/categorias/store')) ?>';document.getElementById('c-id').value='';});
</script>
