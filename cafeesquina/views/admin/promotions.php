<div class="flex justify-between"><h1 class="text-2xl font-bold">Promociones</h1>
<button onclick="openModal('m-promo')" class="btn-primary text-sm">Nueva promoción</button></div>
<div class="grid md:grid-cols-2 gap-4 mt-6">
<?php foreach ($promotions as $p): ?>
<div class="bg-white rounded-xl shadow p-4 flex gap-4">
<img src="<?= e($p['image']) ?>" class="w-24 h-24 object-cover rounded-lg">
<div class="flex-1"><h3 class="font-bold"><?= e($p['title']) ?></h3><p class="text-sm text-gray-600"><?= e($p['description']) ?></p>
<p class="text-xs mt-1"><?= e($p['start_date']) ?> — <?= e($p['end_date']) ?></p>
<div class="mt-2"><button class="text-gold text-sm" onclick='editPromo(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>Editar</button>
<form method="post" action="<?= e(base_url('admin/promociones/delete')) ?>" class="inline" data-confirm-delete><?= ce_csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="text-red-600 text-sm ml-2">Eliminar</button></form></div>
</div></div>
<?php endforeach; ?>
</div>
<div id="m-promo" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
<div class="bg-white rounded-2xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
<h2 class="font-bold">Promoción</h2>
<form id="promo-form" method="post" action="<?= e(base_url('admin/promociones/store')) ?>" enctype="multipart/form-data" class="mt-4 space-y-3"><?= ce_csrf_field() ?>
<input type="hidden" name="id" id="p-id">
<input name="title" id="p-title" required class="input-field" placeholder="Título">
<textarea name="description" id="p-desc" required class="input-field" rows="2"></textarea>
<input name="start_date" id="p-start" type="date" required class="input-field">
<input name="end_date" id="p-end" type="date" required class="input-field">
<input name="image_url" id="p-img" class="input-field" placeholder="URL imagen">
<input type="file" name="image" accept="image/*">
<label class="text-sm"><input type="checkbox" name="active" id="p-active" value="1" checked> Activa</label>
<div class="flex gap-2 justify-end"><button type="button" onclick="closeModal('m-promo')" class="px-4 py-2 border rounded-full">Cancelar</button><button class="btn-primary">Guardar</button></div>
</form></div></div>
<script>
function openModal(id){document.getElementById(id).classList.replace('hidden','flex');}
function closeModal(id){document.getElementById(id).classList.replace('flex','hidden');}
function editPromo(p){document.getElementById('promo-form').action='<?= e(base_url('admin/promociones/update')) ?>';document.getElementById('p-id').value=p.id;document.getElementById('p-title').value=p.title;document.getElementById('p-desc').value=p.description;document.getElementById('p-start').value=p.start_date;document.getElementById('p-end').value=p.end_date;document.getElementById('p-img').value=p.image||'';document.getElementById('p-active').checked=p.active==1;openModal('m-promo');}
</script>
