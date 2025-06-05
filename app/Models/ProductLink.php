<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'platform_id',
        'url',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
