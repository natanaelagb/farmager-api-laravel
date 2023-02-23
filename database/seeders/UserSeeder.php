<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Vinícius Felipe',
            'email' => 'viniciusfbb@google.com',
            'password' => Hash::make('senha'),
            'occupation' => 'Gerente',
            'salary' => 2.00,
            'is_admin' => true,
        ]);
        User::create([
            'name' => 'Natanael Aguilar Barreto',
            'email' => 'natanael@google.com',
            'password' => Hash::make('senha'),
            'occupation' => 'Proprietario',
            'salary' => 2.50,
            'is_admin' => true,
        ]);
        User::create([
            'name' => 'Neymar da Silva Santos Junior',
            'email' => 'neymar@google.com',
            'password' => Hash::make('senha'),
            'occupation' => 'Vaqueiro',
            'salary' => 1.50,
            'is_admin' => true,
        ]);
    }
}
