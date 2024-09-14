<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Event;
use App\Models\Base;

class Service extends Base
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['capacity', 'free_capacity'];

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function getCapacityAttribute()
    {
        return $this->serviceItems()->count();
    }

    public function getFreeCapacityAttribute()
    {
        return $this->serviceItems()->whereNull('passenger_id')->count();
    }

    public static function boot()
    {
        parent::boot();

        Event::listen(['service.index'], function ($query) {
            $query->with(['serviceItems' => fn($q) => $q->with('passenger')->without('service')]);
        });

        Event::listen(['service.created'], function ($service) {
            $serviceItemCount = request(key: 'Service')['capacity'];

            foreach (range(1, $serviceItemCount) as $number) {
                ServiceItem::create([
                    'service_id' => $service->id,
                    'number' => $number,
                    'status' => 'healthy',
                ]);
            }
        });
    }
}
