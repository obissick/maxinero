<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceStats extends Model
{
    protected $table = 'service_stats';

    protected $fillable = [
        'setting_id', 'service_id', 'connections', 'total_connections', 'queries',
    ];
}
