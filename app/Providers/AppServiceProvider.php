<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Province;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $mapModels = [
            'Model.City' => City::class,
            'Model.Province' => Province::class,
        ];

        foreach ($mapModels as $key => $model) {
            $this->app->bind($key, $model);
        }
    }
}
