<?php

namespace Vptrading\TelebirrUssd;

use Illuminate\Support\ServiceProvider;

class TelebirrUssdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/telebirrussd.php' => config_path('telebirrussd.php')
        ]);
    }

    public function register()
    {
        $this->app->singleton('telebirr-ussd', function () {
            return new TelebirrUssd;
        });
    }
}
