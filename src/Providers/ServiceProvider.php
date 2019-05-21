<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-8-31 下午7:44
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

namespace LaravelLicense\Provider;

use Collective\Annotations\AnnotationsServiceProvider;
use LaravelLicense\Command\Encode;
use LaravelLicense\Command\Generate;
use LaravelLicense\Command\RouteRebuild;

class ServiceProvider extends AnnotationsServiceProvider
{
    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [];

    /**
     * The classes to scan for model annotations.
     *
     * @var array
     */
    protected $scanModels = [];

    /**
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

    /**
     * Determines whether or not to automatically scan the controllers
     * directory (App\Http\Controllers) for routes
     *
     * @var bool
     */
    protected $scanControllers = true;

    /**
     * Determines whether or not to automatically scan all namespaced
     * classes for event, route, and model annotations.
     *
     * @var bool
     */
    protected $scanEverything = false;


    public function boot()
    {
        // Register commands
        $this->commands('command.entrust.encode');
        $this->commands('command.entrust.generate');
        $this->commands('command.route.rebuild');

        $this->addRoutingAnnotations($this->app->make('annotations.route.scanner'));

        if (!$this->app->routesAreCached()) {
            $this->loadAnnotatedRoutes();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouteScanner();
        $this->registerCommands();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.entrust.encode', function ($app) {
            return new Encode();
        });

        $this->app->singleton('command.entrust.generate', function ($app) {
            return new Generate();
        });
        $this->app->singleton('command.route.rebuild', function ($app) {
            return new RouteRebuild($app['files'], $this);
        });
    }
}