<?php

namespace Database\Seeders;

use App\Http\Controllers\Dashboard\InstitutionalController;
use App\Models\Institutional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Institutional::insert([
            [
                'title' => 'Razão Social',
                'value' => 'Tecnologia e Inovação Ltda',
                'code' => 'company_name',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Endereço',
                'value' => 'Rua teste, 123',
                'code' => 'address',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'telefone',
                'value' => '(11) 1234-5678',
                'code' => 'phone',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Cidade',
                'value' => 'São Paulo',
                'code' => 'city',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Bairro',
                'value' => 'Jardim floresta',
                'code' => 'neighborhood',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
