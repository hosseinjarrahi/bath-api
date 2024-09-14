<?php

namespace App\Models;

class Province extends Base
{
    protected $guarded = ['id'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
