<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerStats extends Model
{
    protected $table = 'server_stats';

    protected $fillable = [
        'setting_id', 'server_id', 'connections', 'total_connections', 'active_operations',
    ];
}
