<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // The test suite fails on lazy-loading N+1s and typos in attribute names.
        Model::shouldBeStrict($this->app->runningUnitTests());

        // Live scorers send an update on every score/minute change, so allow a generous rate.
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)->by(
            $request->user() ? $request->user()::class.':'.$request->user()->getKey() : $request->ip()
        ));
    }
}
