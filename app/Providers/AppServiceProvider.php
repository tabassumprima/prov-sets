<?php

namespace App\Providers;

use Illuminate\Support\Facades\{Blade, URL};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('authorize', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->authorizePermission({$permission})): ?>";
        });

        Blade::directive('endauthorize', function () {
            return "<?php endif; ?>";
        });

        if (config('app.FORCE_HTTPS')) {
            URL::forceScheme('https');
        }
    }
}
