<?php namespace DoSomething\StatHat;

use Illuminate\Support\ServiceProvider;

class StatHatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ...
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function () {
            return new Client(config('services.stathat'));
        });

        // Set alias for facade / requesting from IoC container
        $this->app->alias(Client::class, 'stathat');
    }

}
