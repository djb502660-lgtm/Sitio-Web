<?php

declare(strict_types=1);

class CartController
{
    public function index(): void
    {
        ce_view('cart/index', [
            'title' => 'Carrito de compras',
            'meta_description' => 'Revisa tu pedido y envíalo por WhatsApp.',
        ]);
    }

    public function checkout(): void
    {
        header('Content-Type: application/json');
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'method']);
            exit;
        }
        if (! ce_csrf_verify_request()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'csrf']);
            exit;
        }
        if (! ce_rate_limit('cart_checkout:' . ce_client_ip(), 20, 3600)) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => 'rate_limit']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $rawItems = $input['items'] ?? [];
        if (! is_array($rawItems) || $rawItems === []) {
            echo json_encode(['ok' => false, 'error' => 'empty']);
            exit;
        }

        $products = new Product();
        $lines = [];
        foreach ($rawItems as $raw) {
            if (! is_array($raw)) {
                continue;
            }
            $productId = (int) ($raw['product_id'] ?? 0);
            $qty = max(1, min(99, (int) ($raw['qty'] ?? 1)));
            $product = $products->find($productId);
            if (! $product || ($product['status'] ?? '') !== 'available') {
                echo json_encode(['ok' => false, 'error' => 'invalid_product', 'product_id' => $productId]);
                exit;
            }
            $lines[] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => (float) $product['price'],
                'qty' => $qty,
            ];
        }

        if ($lines === []) {
            echo json_encode(['ok' => false, 'error' => 'empty']);
            exit;
        }

        $userId = auth_user()['id'] ?? null;
        (new Order())->logLines($userId, $lines);

        echo json_encode([
            'ok' => true,
            'wa_url' => whatsapp_cart_url($lines),
        ]);
        exit;
    }
}
