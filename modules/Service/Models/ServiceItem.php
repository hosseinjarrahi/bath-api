<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class ServiceItem extends Base
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['service'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function passengerForms()
    {
        return $this->belongsToMany(PassengerForm::class);
    }
}
