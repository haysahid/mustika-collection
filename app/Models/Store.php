<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'logo',
        'banner',
    ];

    public function advantages()
    {
        return $this->hasMany(StoreAdvantage::class);
    }

    public function certificates()
    {
        return $this->hasMany(StoreCertificate::class);
    }

    public function social_links()
    {
        return $this->hasMany(StoreSocialLink::class);
    }
}
