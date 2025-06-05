<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'icon',
    ];

    public function product_links()
    {
        return $this->hasMany(ProductLink::class);
    }
}
