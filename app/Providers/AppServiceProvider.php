<?php

namespace App\Providers;

use App\Repository\ZipCodeRepository;
use App\Repository\ZipCodeRepositoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(ZipCodeRepositoryContract::class, ZipCodeRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
