<?php

namespace App\Providers;

use App\Support\Tenancy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 🔒 KRITIS: Tenancy HARUS singleton!
        // Tanpa ini, app(Tenancy::class) mengembalikan instance baru setiap resolve
        // → global scope BelongsToTenant TIDAK memfilter data antar tenant.
        $this->app->singleton(Tenancy::class);

        // 🌉 Jembatan kompatibilitas: app('currentTenant') → Tenancy::tenant()
        // Beberapa controller lama memakai app('currentTenant'). Binding ini
        // memastikan resolusi mengarah ke tenant aktif dari singleton Tenancy
        // (sumber kebenaran tunggal). Tidak di-singleton agar selalu mengikuti
        // tenant aktif terkini per-request.
        $this->app->bind('currentTenant', fn ($app) => $app->make(Tenancy::class)->tenant());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
