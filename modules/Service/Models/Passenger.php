<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Passenger extends Base
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['full_name', 'has_residence'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function passengerForms()
    {
        return $this->hasMany(PassengerForm::class);
    }

    public function passengerForm()
    {
        return $this->passengerForms()->whereNull('checkout_date')->latest()->first();
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function getHasResidenceAttribute()
    {
        return $this->passengerForms()->whereNull('checkout_date')->count() > 0;
    }
}
