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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false]);
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
