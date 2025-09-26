<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCollection extends Model
{
    protected $casts = [
        'date' => 'date',
    ];
    protected $fillable = [
        'user_id', 'shop_id', 'date', 'till_time',
        'online_collection', 'offline_collection', 'total_collection',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
