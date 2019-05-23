<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'api_url', 'username', 'password', 'user_id', 'name',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
