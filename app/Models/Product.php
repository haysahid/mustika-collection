<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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

    protected $appends = [
        'lowest_base_selling_price',
        'lowest_final_selling_price',
        'highest_base_selling_price',
        'highest_final_selling_price',
    ];

    // Additional attributes
    public function getLowestBaseSellingPriceAttribute()
    {
        return $this->variants()->min('base_selling_price');
    }

    public function getLowestFinalSellingPriceAttribute()
    {
        return $this->variants()->min('final_selling_price');
    }

    public function getHighestBaseSellingPriceAttribute()
    {
        return $this->variants()->max('base_selling_price');
    }

    public function getHighestFinalSellingPriceAttribute()
    {
        return $this->variants()->max('final_selling_price');
    }


    // Relationships
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
