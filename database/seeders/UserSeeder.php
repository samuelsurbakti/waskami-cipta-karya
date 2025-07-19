<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dev = User::create([
            'name' => 'Sammy',
            'username' => 'sammy',
            'email' => 'sammysurbakti@gmail.com',
            'password' => bcrypt('sammy'),
            'avatar' => 'null.png',
            'account_status' => '1',
        ]);
    }
}
