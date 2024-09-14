<?php

namespace Modules\Payment\Models;

use App\Models\Base;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Base
{
    protected $guarded = ['id'];

    protected $casts = [
        'response' => 'array'
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
}
