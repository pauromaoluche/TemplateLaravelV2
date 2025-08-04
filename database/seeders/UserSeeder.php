<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
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

        $sourceImagePath = public_path('images/goku.jpg');

        $folderPath = 'images/user';

        $fileExtension = pathinfo($sourceImagePath, PATHINFO_EXTENSION);
        $fileName = Str::uuid() . '.' . $fileExtension;
        $destinationPath = $folderPath . '/' . $fileName;

        Storage::disk('public')->put($destinationPath, file_get_contents($sourceImagePath));

        $user->images()->create([
            'path' => $destinationPath,
        ]);
    }
}
