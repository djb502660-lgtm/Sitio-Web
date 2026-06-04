<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CafeesquinaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            base_path('config/cafeesquina.php'),
            'cafeesquina'
        );
    }

    public function boot(): void
    {
        //
    }
}
