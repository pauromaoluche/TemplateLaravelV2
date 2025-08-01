<?php

namespace App\Providers;

use App\Models\Institutional;
use App\Models\User;
use App\Policies\InstitutionalPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Institutional::class, InstitutionalPolicy::class);

        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });
    }
}
