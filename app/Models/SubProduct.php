<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubProduct extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $appends = ['complete_name'];

    protected $fillable = [
        'store_id',
        'parent_id',
        'code',
        'name',
        'slug',
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

    public function getCompleteNameAttribute()
    {
        return $this->parent ? $this->parent->name . ' - ' . $this->name : $this->name;
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
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

    public function transaction_items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
