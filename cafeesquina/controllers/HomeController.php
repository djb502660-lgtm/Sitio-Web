<?php

declare(strict_types=1);

class HomeController
{
    public function index(): void
    {
        $productModel = new Product();
        $promoModel = new Promotion();
        ce_view('home/index', [
            'title' => 'CAFEESQUINA — ' . app_config('tagline'),
            'featured' => $productModel->featured(6),
            'bestSellers' => $productModel->bestSellers(4),
            'categories' => (new Category())->all(),
            'promotions' => $promoModel->active(),
            'meta_description' => 'Cafetería artesanal CAFEESQUINA. Café de especialidad, postres y pedidos por WhatsApp.',
        ]);
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $base = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . base_url('');
        $urls = ['', 'menu', 'login', 'register'];
        echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            echo '<url><loc>' . htmlspecialchars($base . ($u ? '/' . $u : '')) . '</loc></url>';
        }
        echo '</urlset>';
        exit;
    }
}
