<?php

declare(strict_types=1);

class AdminController
{
    public function dashboard(): void
    {
        require_admin();
        $pm = new Product();
        $cm = new Category();
        $um = new User();
        $om = new Order();
        ce_view('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => [
                'products' => $pm->countAll(),
                'categories' => $cm->countAll(),
                'users' => $um->countAll(),
                'orders' => $om->countAll(),
                'promotions' => (new Promotion())->countActive(),
            ],
            'topSelling' => $pm->topSelling(5),
            'recentOrders' => $om->recent(8),
        ], 'admin');
    }

    /* --- Productos --- */
    public function products(): void
    {
        require_admin();
        $pm = new Product();
        ce_view('admin/products', [
            'title' => 'Productos',
            'products' => $pm->all(
                isset($_GET['categoria']) ? (int) $_GET['categoria'] : null,
                sanitize_string((string) ($_GET['q'] ?? ''), 100) ?: null,
                $_GET['status'] ?? null
            ),
            'categories' => (new Category())->all(),
        ], 'admin');
    }

    public function storeProduct(): void
    {
        require_admin();
        $this->guardPost();
        $d = $this->productData($_POST, $_FILES);
        if ($d['errors']) {
            flash('error', implode(' ', $d['errors']));
            ce_redirect('admin/productos');
        }
        (new Product())->create($d['data']);
        flash('success', 'Producto creado.');
        ce_redirect('admin/productos');
    }

    public function updateProduct(): void
    {
        require_admin();
        $this->guardPost();
        $id = (int) ($_POST['id'] ?? 0);
        $existing = (new Product())->find($id);
        if (!$existing) {
            flash('error', 'Producto no encontrado.');
            ce_redirect('admin/productos');
        }
        $d = $this->productData($_POST, $_FILES, $existing['image']);
        if ($d['errors']) {
            flash('error', implode(' ', $d['errors']));
            ce_redirect('admin/productos');
        }
        (new Product())->update($id, $d['data']);
        flash('success', 'Producto actualizado.');
        ce_redirect('admin/productos');
    }

    public function deleteProduct(): void
    {
        require_admin();
        $this->guardPost();
        (new Product())->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Producto eliminado.');
        ce_redirect('admin/productos');
    }

    /* --- Categorías --- */
    public function categories(): void
    {
        require_admin();
        ce_view('admin/categories', ['title' => 'Categorías', 'categories' => (new Category())->all()], 'admin');
    }

    public function storeCategory(): void
    {
        require_admin();
        $this->guardPost('admin/categorias');
        $name = sanitize_string((string) ($_POST['name'] ?? ''), 80);
        if ($name === '') {
            flash('error', 'Nombre obligatorio.');
            ce_redirect('admin/categorias');
        }
        $category = new Category();
        if ($category->findByName($name) !== null) {
            flash('error', 'Ya existe una categoría con ese nombre.');
            ce_redirect('admin/categorias');
        }
        $category->create($name, sanitize_string((string) ($_POST['description'] ?? ''), 255));
        flash('success', 'Categoría creada.');
        ce_redirect('admin/categorias');
    }

    public function updateCategory(): void
    {
        require_admin();
        $this->guardPost('admin/categorias');
        $id = (int) ($_POST['id'] ?? 0);
        $name = sanitize_string((string) ($_POST['name'] ?? ''), 80);
        if ($id <= 0 || $name === '') {
            flash('error', 'Datos de categoría incompletos.');
            ce_redirect('admin/categorias');
        }
        $category = new Category();
        if ($category->find($id) === null) {
            flash('error', 'Categoría no encontrada.');
            ce_redirect('admin/categorias');
        }
        if ($category->findByName($name, $id) !== null) {
            flash('error', 'Ya existe otra categoría con ese nombre.');
            ce_redirect('admin/categorias');
        }
        $category->update(
            $id,
            $name,
            sanitize_string((string) ($_POST['description'] ?? ''), 255)
        );
        flash('success', 'Categoría actualizada.');
        ce_redirect('admin/categorias');
    }

    public function deleteCategory(): void
    {
        require_admin();
        $this->guardPost('admin/categorias');
        if (!(new Category())->delete((int) ($_POST['id'] ?? 0))) {
            flash('error', 'No se puede eliminar: tiene productos asociados.');
        } else {
            flash('success', 'Categoría eliminada.');
        }
        ce_redirect('admin/categorias');
    }

    /* --- Promociones --- */
    public function promotions(): void
    {
        require_admin();
        ce_view('admin/promotions', ['title' => 'Promociones', 'promotions' => (new Promotion())->all()], 'admin');
    }

    public function storePromotion(): void
    {
        require_admin();
        $this->guardPost();
        $d = $this->promotionData($_POST, $_FILES);
        if ($d['errors']) {
            flash('error', implode(' ', $d['errors']));
            ce_redirect('admin/promociones');
        }
        (new Promotion())->create($d['data']);
        flash('success', 'Promoción creada.');
        ce_redirect('admin/promociones');
    }

    public function updatePromotion(): void
    {
        require_admin();
        $this->guardPost();
        $id = (int) ($_POST['id'] ?? 0);
        $ex = (new Promotion())->find($id);
        if (!$ex) {
            flash('error', 'Promoción no encontrada.');
            ce_redirect('admin/promociones');
        }
        $d = $this->promotionData($_POST, $_FILES, $ex['image']);
        if ($d['errors']) {
            flash('error', implode(' ', $d['errors']));
            ce_redirect('admin/promociones');
        }
        (new Promotion())->update($id, $d['data']);
        flash('success', 'Promoción actualizada.');
        ce_redirect('admin/promociones');
    }

    public function deletePromotion(): void
    {
        require_admin();
        $this->guardPost();
        (new Promotion())->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Promoción eliminada.');
        ce_redirect('admin/promociones');
    }

    /* --- Ubicación --- */
    public function locationSettings(): void
    {
        require_admin();
        (new SiteSetting())->ensureDefaults();
        ce_view('admin/location', [
            'title' => 'Ubicación',
            'settings' => [
                'address' => (string) site_config('address'),
                'hours' => (string) site_config('hours'),
                'map_embed' => (string) site_config('map_embed'),
            ],
        ], 'admin');
    }

    public function updateLocationSettings(): void
    {
        require_admin();
        $this->guardPost('admin/ubicacion');
        $address = sanitize_string((string) ($_POST['address'] ?? ''), 255);
        $hours = sanitize_string((string) ($_POST['hours'] ?? ''), 255);
        $mapEmbed = trim((string) ($_POST['map_embed'] ?? ''));
        if ($address === '' || $hours === '' || $mapEmbed === '') {
            flash('error', 'Completa dirección, horario y URL del mapa.');
            ce_redirect('admin/ubicacion');
        }
        if (! filter_var($mapEmbed, FILTER_VALIDATE_URL)) {
            flash('error', 'URL del mapa inválida.');
            ce_redirect('admin/ubicacion');
        }
        (new SiteSetting())->setMany([
            'address' => $address,
            'hours' => $hours,
            'map_embed' => $mapEmbed,
        ]);
        flash('success', 'Ubicación actualizada.');
        ce_redirect('admin/ubicacion');
    }

    /* --- Usuarios --- */
    public function users(): void
    {
        require_admin();
        ce_view('admin/users', ['title' => 'Usuarios', 'users' => (new User())->all()], 'admin');
    }

    public function updateUser(): void
    {
        require_admin();
        $this->guardPost();
        $id = (int) ($_POST['id'] ?? 0);
        (new User())->update($id, [
            'username' => sanitize_string((string) ($_POST['username'] ?? ''), 50),
            'email' => (string) filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'full_name' => sanitize_string((string) ($_POST['full_name'] ?? ''), 100),
            'phone' => sanitize_string((string) ($_POST['phone'] ?? ''), 20),
            'role' => in_array($_POST['role'] ?? '', ['client', 'admin'], true) ? $_POST['role'] : 'client',
        ]);
        flash('success', 'Usuario actualizado.');
        ce_redirect('admin/usuarios');
    }

    public function deleteUser(): void
    {
        require_admin();
        $this->guardPost();
        if (!(new User())->delete((int) ($_POST['id'] ?? 0))) {
            flash('error', 'No se puede eliminar este usuario.');
        } else {
            flash('success', 'Usuario eliminado.');
        }
        ce_redirect('admin/usuarios');
    }

    private function guardPost(string $fallback = 'admin'): void
    {
        if (ce_request_method() !== 'POST' || !ce_csrf_verify()) {
            flash('error', 'Solicitud inválida.');
            ce_redirect($fallback);
        }
    }

    private function productData(array $post, array $files, ?string $currentImage = null): array
    {
        $errors = [];
        $name = sanitize_string((string) ($post['name'] ?? ''), 150);
        $desc = sanitize_string((string) ($post['description'] ?? ''), 2000);
        $price = filter_var(str_replace(',', '.', (string) ($post['price'] ?? '')), FILTER_VALIDATE_FLOAT);
        $catId = (int) ($post['category_id'] ?? 0);
        $status = ($post['status'] ?? '') === 'unavailable' ? 'unavailable' : 'available';
        $featured = !empty($post['featured']) ? 1 : 0;
        $image = $this->uploadImage($files['image'] ?? null) ?? trim((string) ($post['image_url'] ?? '')) ?: $currentImage;

        if ($name === '' || $desc === '' || $price === false || $catId <= 0) {
            $errors[] = 'Completa nombre, descripción, precio y categoría.';
        }
        if (!$image) {
            $errors[] = 'Imagen requerida (archivo o URL).';
        }

        return [
            'errors' => $errors,
            'data' => [
                'category_id' => $catId,
                'name' => $name,
                'description' => $desc,
                'price' => (float) $price,
                'image' => $image,
                'status' => $status,
                'featured' => $featured,
            ],
        ];
    }

    private function promotionData(array $post, array $files, ?string $currentImage = null): array
    {
        $errors = [];
        $title = sanitize_string((string) ($post['title'] ?? ''), 150);
        $desc = sanitize_string((string) ($post['description'] ?? ''), 2000);
        $start = (string) ($post['start_date'] ?? '');
        $end = (string) ($post['end_date'] ?? '');
        $image = $this->uploadImage($files['image'] ?? null, 'promotions') ?? trim((string) ($post['image_url'] ?? '')) ?: $currentImage;
        $active = !empty($post['active']) ? 1 : 0;

        if ($title === '' || $desc === '' || !$start || !$end) {
            $errors[] = 'Completa título, descripción y fechas.';
        }
        if (!$image) {
            $errors[] = 'Imagen requerida.';
        }

        return [
            'errors' => $errors,
            'data' => [
                'title' => $title,
                'description' => $desc,
                'image' => $image,
                'start_date' => $start,
                'end_date' => $end,
                'active' => $active,
            ],
        ];
    }

    private function uploadImage(?array $file, string $subdir = 'products'): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? 0) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 3 * 1024 * 1024) {
            return null;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
        if (!$ext) {
            return null;
        }
        $dir = dirname(__DIR__) . '/uploads/' . $subdir;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = uniqid('img_', true) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return null;
        }
        return upload_url($subdir . '/' . $name);
    }
}
