<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'slug',
        'motif',
        'color_id',
        'size_id',
        'material',
        'purchase_price',
        'base_selling_price',
        'discount_type',
        'discount',
        'final_selling_price',
        'current_stock_level',
        'last_stock_update',
        'unit',
        'disabled_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function images()
    {
        return $this->hasMany(ProductVariantImage::class)->orderBy('order', 'asc');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
