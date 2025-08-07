<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::insert([
            [
                'title' => 'Memória Servidor -mb',
                'value' => '200',
                'code' => 'server_max_size'
            ],
            [
                'title' => 'Manutenção',
                'value' => '0',
                'code' => 'maintenance'
            ],
            [
                'title' => 'Email',
                'value' => 'email@gmail.com',
                'code' => 'email'
            ],
            [
                'title' => 'Titulo do site',
                'value' => 'PS - Solutions',
                'code' => 'site_name'
            ],
            [
                'title' => 'Icone do site',
                'value' => 'Icone do site',
                'code' => 'icon'
            ]
        ]);
    }
}
