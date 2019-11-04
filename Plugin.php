<?php namespace Inetis\Testing;

use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\Facades\App;
use Inetis\Testing\Http\Controllers\DuskUserController;
use Route;
use System\Classes\PluginBase;

/**
 * Dump Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Testing',
            'description' => 'Add dependecies for run Laravel Dusk on OctoberCMS',
            'author'      => 'inetis',
            'icon'        => 'icon-code',
        ];
    }

    public function boot()
    {
        if (!App::environment('dusk')) {
            return;
        }

        Route::get('/_dusk/login/{userId}/{provider}', [
            'middleware' => 'web',
            'uses'       => DuskUserController::class . '@login',
        ]);

        Route::get('/_dusk/logout/{provider?}', [
            'middleware' => 'web',
            'uses'       => DuskUserController::class . '@logout',
        ]);

        Route::get('/_dusk/user/{provider}', [
            'middleware' => 'web',
            'uses'       => DuskUserController::class . '@user',
        ]);
    }

    public function register()
    {
        $this->app->singleton(EloquentFactory::class, function ($app) {
            return EloquentFactory::construct(
                $app->make(FakerGenerator::class), plugins_path('inetis/testing/factories')
            );
        });
    }
}
