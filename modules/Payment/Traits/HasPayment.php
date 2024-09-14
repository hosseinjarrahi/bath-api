<?php

namespace Modules\Payment\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Payment\Models\Payment;

trait HasPayment
{
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function getPayedAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getPaymentableAttribute()
    {
        return $this::class;
    }
}
