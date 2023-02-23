<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        // 'animal_id',
        'description',
        'amount',
        'unit',
        'unit_price'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function production()
    {
        return $this->hasMany(Production::class);
    }

    public function transaction()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
