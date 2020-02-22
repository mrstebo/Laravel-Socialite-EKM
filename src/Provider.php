<?php

namespace Mrstebo\LaravelSocialiteEKM;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $config = config()->get('services.ekm');

        $provider = $socialite->buildProvider(EKMSocialiteProvider::class, $config);

        $socialite->extend('ekm', function () use ($provider) {
            return $provider;
        });
    }
}
