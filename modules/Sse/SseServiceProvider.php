<?php

namespace Modules\Sse;

use App\Providers\ModuleServiceProvider;

class SseServiceProvider extends ModuleServiceProvider
{
    function getNamespace()
    {
        return 'Sse\Http\Controllers';
    }

    function getDir()
    {
        return __DIR__;
    }
}
