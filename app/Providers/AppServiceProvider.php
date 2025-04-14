<?php

namespace App\Providers;
use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Schema;

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
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Schema::defaultStringLength(191);

    }
}
