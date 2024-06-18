<?php
namespace Mhasnainjafri\APIToolkit\Tests;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\ResponseFactory as IlluminateResponseFactory;

class TestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ResponseFactory::class, function ($app) {
            return new IlluminateResponseFactory(
                $app['Illuminate\Contracts\View\Factory'],
                $app['Illuminate\Routing\Redirector']
            );
        });
    }

    public function boot()
    {
        //
    }
}
