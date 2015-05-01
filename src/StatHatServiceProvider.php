<?php namespace DoSomething\StatHat;

use Illuminate\Support\ServiceProvider;

class StatHatServiceProvider extends ServiceProvider
{

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $app = $this->app;
    $app['stathat'] = $app->share(function ($app) {
      return new StatHat($app['config']->get('services.stathat'));
    });
  }

}