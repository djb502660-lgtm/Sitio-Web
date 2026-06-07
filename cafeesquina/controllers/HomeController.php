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
        $xml = $this->buildSitemapXml();

        if (function_exists('app') && class_exists(\Illuminate\Foundation\Application::class)) {
            try {
                $app = app();
                if ($app->bound('router')) {
                    throw new \Illuminate\Http\Exceptions\HttpResponseException(
                        response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8'])
                    );
                }
            } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
                throw $e;
            } catch (\Throwable) {
                // Fallback standalone abajo.
            }
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo $xml;
    }

    private function buildSitemapXml(): string
    {
        $base = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . base_url('');
        $urls = ['', 'menu', 'login', 'register'];
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            $xml .= '<url><loc>' . htmlspecialchars($base . ($u ? '/' . $u : '')) . '</loc></url>';
        }
        $xml .= '</urlset>';

        return $xml;
    }
}
