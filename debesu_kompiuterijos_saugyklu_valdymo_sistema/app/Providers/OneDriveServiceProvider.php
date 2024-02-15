<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Microsoft\Graph\Graph;
use League\Flysystem\Filesystem;
use App\Adapters\OneDriveFlySystemAdapter;

class OneDriveServiceProvider extends ServiceProvider
{
 /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        \Storage::extend('onedrive', function($app, $config) {
            $graph = new Graph();
            $graph->setAccessToken( $config['access_token']);      
            $adapter = new OneDriveFlySystemAdapter($graph, 'special/approot');
            return new Filesystem($adapter);
        });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
