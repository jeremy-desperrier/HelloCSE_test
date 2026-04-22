<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // j'ai une version un peu ancienne de mysql ça me permet de ne pas faire planter les migration de base de la table users.
        Schema::defaultStringLength(191);
    }
}
