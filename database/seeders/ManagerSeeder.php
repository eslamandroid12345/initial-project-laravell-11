<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manager::query()->create([
            'name' => 'Admin',
            'email' => 'admin@elryad.com',
            'phone' => '+96650000000',
            'password' => 'elryad1256!#'
        ]);
    }
}
