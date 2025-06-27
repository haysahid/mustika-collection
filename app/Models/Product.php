<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'brand_id',
        'name',
        'slug',
        'sku_prefix',
        'description',
        'discount_type',
        'discount',
        'disabled_at',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function links()
    {
        return $this->hasMany(ProductLink::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
