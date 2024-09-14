<?php

namespace App\Models;

class City extends Base
{
    protected $guarded = ['id'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
