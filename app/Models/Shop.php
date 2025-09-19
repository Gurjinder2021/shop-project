<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['user_id', 'shop_number', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyCollections()
    {
        return $this->hasMany(DailyCollection::class);
    }
}
