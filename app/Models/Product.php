<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'code',
        'name',
        'description',
        'material',
        'selling_price',
        'discount',
        'stock',
        'min_order',
        'unit',
        'color_id',
        'brand_id',
        'disabled_at',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function links()
    {
        return $this->hasMany(ProductLink::class);
    }
}
