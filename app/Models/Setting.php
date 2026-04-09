<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'api_url', 'username', 'password', 'user_id', 'name', 'selected',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
