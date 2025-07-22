<?php

namespace Database\Seeders;

use App\Models\SLP\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Developer',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'Direksi',
            'guard_name' => 'web',
        ]);
    }
}
