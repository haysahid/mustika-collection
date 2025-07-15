<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type_id',
        'code',
        'note',
        'payment_method_id',
        'shipping_method_id',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'address',
        'shipping_estimate',
        'shipping_cost',
        'paid_at',
        'shipped_at',
        'picked_up_at',
        'delivered_at',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'type_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
