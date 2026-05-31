<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['user_id', 'name', 'contact', 'email', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}