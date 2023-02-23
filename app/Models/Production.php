<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productions';

    protected $fillable = [
        'animal_id',
        'product_id',
        'amount',
        'description',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
