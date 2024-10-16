<?php

namespace Modules\Service;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
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

        $this->setDynamicConnection();
    }


    protected function setDynamicConnection()
    {
        // Determine the connection from your dynamic criteria
        $connectionName = $this->getConnectionNameBasedOnLogic();
        // Set the default connection
        Config::set('database.default', $connectionName);
    }

    protected function getConnectionNameBasedOnLogic()
    {
        $headerValue = Request::header('X-Database-Connection'); // Your custom header name

        if ($headerValue === 'west') {
            return 'mysql_west';
        }

        if ($headerValue === 'east') {
            return 'mysql_east';
        }
        // Default connection
        return 'mysql_west';
    }
}
