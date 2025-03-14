<?php

namespace App\Providers;

use App\Helpers\JubelioHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;

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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });

        // Eksekusi cache jubelio setiap pertama kali halaman dimuat
        view()->share('jublioCache', JubelioHelper::getJubelioCache());

    }
}
