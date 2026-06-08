<?php

declare(strict_types=1);

class ProductController
{
    private Product $products;

    public function __construct()
    {
        $this->products = new Product();
    }

    public function menu(): void
    {
        $categoryId = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;
        $search = sanitize_string((string) ($_GET['q'] ?? ''), 100) ?: null;
        ce_view('products/menu', [
            'title' => 'Menú digital',
            'products' => $this->products->all($categoryId, $search, 'available'),
            'categories' => (new Category())->all(),
            'currentCategory' => $categoryId,
            'search' => $search,
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $product = $this->products->find($id);
        if (!$product) {
            flash('error', 'Producto no encontrado.');
            ce_redirect('menu');
        }
        ce_view('products/show', ['title' => $product['name'], 'product' => $product]);
    }

    public function logOrder(): void
    {
        header('Content-Type: application/json');
        if (ce_request_method() !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'method']);
            exit;
        }
        if (! ce_csrf_verify_request()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'csrf']);
            exit;
        }
        if (! ce_rate_limit('log_order:' . ce_client_ip(), 30, 3600)) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => 'rate_limit']);
            exit;
        }
        if (! is_logged_in()) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'auth', 'redirect' => base_url('login?compra=1')]);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $productId = (int) ($input['product_id'] ?? 0);
        $product = $this->products->find($productId);
        if (!$product) {
            echo json_encode(['ok' => false]);
            exit;
        }
        $userId = auth_user()['id'] ?? null;
        (new Order())->log($userId, $productId, $product['name'], (float) $product['price']);
        echo json_encode(['ok' => true]);
        exit;
    }
}
