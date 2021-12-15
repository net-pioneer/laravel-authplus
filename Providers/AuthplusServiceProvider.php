<?php

namespace netpioneer\authplus\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use netpioneer\authplus\Classes\AuthPlus;
use netpioneer\authplus\Classes\Common\AuthPlusAuthenticateUserDefualt;
use netpioneer\authplus\Classes\Common\AuthPlusRedirectAuthenticatedCustom;
use netpioneer\authplus\Classes\Common\AuthPlusRedirectAuthenticatedDefault;
use netpioneer\authplus\Classes\Common\AuthPlusTwoFactorSmsAuthenticator;

class AuthplusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/../routes/custom-auth.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/2021_12_13_070240_create_ap_table.php');
        //$this->loadTranslationsFrom(__DIR__.'/ap_lang_en.php', 'AuthPlus');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'AuthPlus');
        $this->publishes([
            __DIR__.'/../auth.php' => config_path('auth.php'),
            __DIR__.'/../Http/Middleware/Authenticate.php'=>app_path('Http/Middleware/Authenticate.php'),
            __DIR__.'/../Http/Middleware/RedirectIfAuthenticated.php'=>app_path('Http/Middleware/RedirectIfAuthenticated.php'),
            __DIR__.'/../RAVIE.TTF'=>public_path('RAVIE.TTF'),
        ]);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        AuthPlus::AuthenticateMethod(AuthPlusAuthenticateUserDefualt::class);
        AuthPlus::RedirectAuthenticated(AuthPlusRedirectAuthenticatedCustom::class);
        AuthPlus::TwoFactorAuthenticator(AuthPlusTwoFactorSmsAuthenticator::class);
    }
}
