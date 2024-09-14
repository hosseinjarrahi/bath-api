<?php

namespace Modules\Service\Models;

use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceOption extends Base
{
    use HasFactory;

    protected $guarded = ['id'];

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class)->withPivot('count');
    }
}
