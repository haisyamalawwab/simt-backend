<?php

namespace Modules\Finance\Providers;

use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Route diregistrasi via RouteServiceProvider (web group + api prefix),
        // konsisten dengan modul lain. Plug & play: provider ini hanya ter-boot
        // saat modul ENABLED di modules_statuses.json (dikelola nwidart).
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'finance');
    }
}
