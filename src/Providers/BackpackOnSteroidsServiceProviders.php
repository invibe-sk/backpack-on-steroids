<?php

namespace Invibe\BackpackOnSteroids\Providers;

use Illuminate\Support\ServiceProvider;
use Invibe\BackpackOnSteroids\Console\Commands\BackpackSteroids;

/**
 * Class BackpackOnSteroidsServiceProviders
 * @author Adam Ondrejkovic
 * @package Invibe\BackpackOnSteroids\Providers
 */
class BackpackOnSteroidsServiceProviders extends ServiceProvider
{
    /**
     * @author Adam Ondrejkovic
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'backpack-on-steroids');
    }

    /**
     * @author Adam Ondrejkovic
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                BackpackSteroids::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('backpack-on-steroids.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../../resources/views/base/inc' => resource_path('views/vendor/backpack/base/inc'),
                __DIR__.'/../../resources/views/crud' => resource_path('views/vendor/backpack/crud'),
            ], 'views');

            if (!class_exists('CreateTempFilesTable')) {
                $this->publishes([
                    __DIR__ . '/../../database/migrations/create_temp_files_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_temp_files_table.php'),
                ], 'migrations');
            }

            $this->publishes([__DIR__ . '/../../public/vendor/backpack-on-steroids/app.js' => public_path('vendor/backpack-on-steroids/app.js')], 'assets');

        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'backpack-on-steroids');

    }
}
