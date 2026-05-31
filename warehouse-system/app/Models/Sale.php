<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'customer_name',
        'customer_contact',
        'quantity',
        'unit_price',
        'total_price',
        'receipt_no',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}