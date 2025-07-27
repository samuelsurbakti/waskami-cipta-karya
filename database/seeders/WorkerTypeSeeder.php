<?php

namespace Database\Seeders;

use App\Models\Hr\Worker\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['tukang', 'kernet', 'pegawai'];

        foreach ($types as $type) {
            Type::create([
                'name' => $type
            ]);
        }
    }
}
