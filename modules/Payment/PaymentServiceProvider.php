<?php

namespace Modules\Payment;

use App\Providers\ModuleServiceProvider;

class PaymentServiceProvider extends ModuleServiceProvider
{
    function getNamespace()
    {
        return 'Payment\Http\Controllers';
    }

    function getDir()
    {
        return __DIR__;
    }
}
