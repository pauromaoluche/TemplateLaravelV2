<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'adminweb@hotmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admwaas@.'),
            'is_admin' => true,
            'remember_token' => Str::random(10),
        ]);
    }
}
