<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'animals_events';

    protected $fillable = [
        'animal_id',
        'description',
        'user_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
