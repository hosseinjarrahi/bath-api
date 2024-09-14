<?php

namespace Modules\Service;

use App\Providers\ModuleServiceProvider;
use Modules\Service\database\seeders\ServiceSettingSeeder;

class ServiceServiceProvider extends ModuleServiceProvider
{
    protected $seedList = [
        ServiceSettingSeeder::class
    ];

    function getNamespace(): string
    {
        return 'Service\Controllers';
    }

    function getDir()
    {
        return __DIR__;
    }

    function boot()
    {
        $this->loadSeeders($this->seedList);
    }
}
