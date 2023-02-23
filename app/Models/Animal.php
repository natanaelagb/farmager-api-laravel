<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'animals';

    protected $fillable = [
        'specie',
        'breed',
        'description',
        'weight',
        'birth_date',
        'vaccines',
        'gender',
        'death_at',
        'father_id',
        'mother_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'deleted_at' => 'datetime',
        'death_at' => 'date',
    ];

    public function events()
    {
        return $this->hasMany(AnimalEvent::class);
    }

    public function products()
    {
        return $this->hasMany(Production::class);
    }

    public function father()
    {
        return $this->belongsTo(Animal::class, 'father_id');;
    }

    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mother_id');;
    }
}
