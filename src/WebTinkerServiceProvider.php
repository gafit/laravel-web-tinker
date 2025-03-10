<?php

namespace Spatie\WebTinker;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\WebTinker\Console\InstallCommand;
use Spatie\WebTinker\Http\Middleware\Authorize;
use Spatie\WebTinker\Http\Controllers\WebTinkerController;

class WebTinkerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/web-tinker.php' => config_path('web-tinker.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/web-tinker'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/web-tinker'),
            ], 'web-tinker-assets');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'web-tinker');

        $this
            ->registerRoutes()
            ->registerWebTinkerGate();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/web-tinker.php', 'web-tinker');

        $this->commands(InstallCommand::class);
    }

    protected function registerRoutes()
    {
        Route::group(['namespace' => 'Spatie\WebTinker\Http\Controllers', 'prefix' => config('web-tinker.path')], function() {
            Route::get('/', 'WebTinkerController@index');
            Route::post('/', 'WebTinkerController@execute');
        });

        return $this;
    }

    protected function registerWebTinkerGate()
    {
        Gate::define('viewWebTinker', function ($user = null) {
            return app()->environment('local');
        });

        return $this;
    }
}
