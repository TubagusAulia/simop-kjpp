<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Auto-create storage symlink if missing (handles fresh deploys / testing setups)
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        if (!file_exists($linkPath) && !is_link($linkPath) && is_dir($targetPath)) {
            @symlink($targetPath, $linkPath);
        }
    }
}
