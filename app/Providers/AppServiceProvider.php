<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

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
        //
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Response::macro('error', function ($message) {
            return response()->json([
                'status' => 'false',
                'message' => $message
            ]);
        });
        Response::macro('success', function ($message) {
            return response()->json([
                'status' => 'true',
                'message' => $message,
            ]);
        });
    }
}
