<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreSocialLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'url',
        'icon',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
