<?php

namespace Modules\Service\Models;

use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSetting extends Base
{
    use HasFactory;

    protected $guarded = ['id'];
}
