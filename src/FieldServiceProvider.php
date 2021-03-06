<?php

namespace Pavinthan\NovaMap;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-map', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-map', __DIR__ . '/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/nova-map.php' => config_path('nova-map.php'),
        ], 'nova-map-config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-map.php', 'nova-map'
        );
    }
}
