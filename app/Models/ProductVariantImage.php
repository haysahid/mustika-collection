<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'product_id',
        'image',
        'order',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
