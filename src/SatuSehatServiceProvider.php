<?php

namespace Rezaandreannn\SatuSehat;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Rezaandreannn\SatuSehat\FHIR\PatientFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class SatuSehatServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            // __DIR__ . '/config/satusehat.php','satusehat'
            __DIR__ . '/../config/satusehat.php',
            'satusehat'
        );

        // Bind service ke container
        $this->app->singleton('satusehat', function ($app) {
            return new SatuSehatService();
        });

        $this->app->singleton(PatientFHIR::class, function () {
            return new PatientFHIR();
        });
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/satusehat.php' => config_path('satusehat.php'),
        ], 'satusehat-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'satusehat-migrations');

        // Load migrations
        // $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Load routes jika diperlukan
        // $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }
}
