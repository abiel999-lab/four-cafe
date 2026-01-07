<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'order_type',
        'table_code',
        'status',
        'subtotal',
        'total',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'total' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getIsPaidAttribute(): bool
    {
        return !is_null($this->paid_at);
    }
}
