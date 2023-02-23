<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use App\Models\Animal;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $animal = Animal::create([
            'specie' => 'Bovino',
            'breed' => 'Nelore',
            'description' => 'Touro bandido',
            'weight' => 1200,
            'birth_date' => Date::create(2012, 12, 30),
            'vaccines' => '',
            'gender' => 'Macho',
        ]);

        $animal = Animal::create([
            'specie' => 'Bovino',
            'breed' => 'Nelore',
            'description' => 'Pintada',
            'weight' => 1200,
            'birth_date' => Date::create(2018, 12, 30),
            'vaccines' => '',
            'gender' => 'Fêmea',
            'father_id' => $animal->id,
        ]);

        $animal->events()->create([
            'description' => 'Entorse detectada',
            'user_id' => '1',
        ]);

        $animal->events()->create([
            'description' => 'Checkup de rotina com veterinário: animal saudável',
            'user_id' => '2',
        ]);

    }
}
