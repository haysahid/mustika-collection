<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'effect_on_stock',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'type_id');
    }
}
