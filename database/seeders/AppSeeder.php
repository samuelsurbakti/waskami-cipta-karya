<?php

namespace Database\Seeders;

use App\Models\Sys\App;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $app_no = 0;

        App::create([
            'name' => 'Accel',
            'subdomain' => 'accel.waskami-cipta-karya.test',
            'image' => 'accel',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelHr',
            'subdomain' => 'hr.waskami-cipta-karya.test',
            'image' => 'accel-hr',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelStock',
            'subdomain' => 'stock.waskami-cipta-karya.test',
            'image' => 'accel-stock',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelBuild',
            'subdomain' => 'build.waskami-cipta-karya.test',
            'image' => 'accel-build',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelTask',
            'subdomain' => 'task.waskami-cipta-karya.test',
            'image' => 'accel-task',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelDocs',
            'subdomain' => 'docs.waskami-cipta-karya.test',
            'image' => 'accel-docs',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelRef',
            'subdomain' => 'ref.waskami-cipta-karya.test',
            'image' => 'accel-ref',
            'order_number' => $app_no++
        ]);
    }
}
