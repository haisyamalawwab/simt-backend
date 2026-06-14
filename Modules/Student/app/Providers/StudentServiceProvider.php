<?php

namespace Modules\Student\Providers;

use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Route diregistrasi via RouteServiceProvider (web group + api prefix),
        // konsisten dengan modul Core. Plug & play: provider ini hanya
        // ter-boot saat modul ENABLED di modules_statuses.json (dikelola nwidart).
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'student');
    }
}
