<?php

namespace Modules\Service\Models;

use App\Models\Base;
use Modules\Payment\Traits\HasPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassengerForm extends Base
{
    use HasFactory, HasPayment;

    protected $guarded = ['id'];

    protected $appends = ['active_service_items', 'is_past', 'payed', 'balance', 'paymentable', 'price'];

    protected $with = [
        'passenger',
    ];

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function serviceOptions()
    {
        return $this->belongsToMany(ServiceOption::class)->withPivot('count');
    }

    public function scopeActiveForm()
    {
        return $this->whereNull('checkout_date');
    }

    public function getActiveServiceItemsAttribute()
    {
        return $this->serviceItems()->wherePivot('active', true)->get();
    }

    public function getIsPastAttribute()
    {
        return now()->gt($this->end_date);
    }

    public function getBalanceAttribute()
    {
        return $this->price - $this->payed;
    }

    public function getPriceAttribute()
    {
        return $this->service_items_price + $this->options_price + $this->damage + $this->delay_price;
    }

    public function serviceItems()
    {
        return $this->belongsToMany(ServiceItem::class);
    }
}
