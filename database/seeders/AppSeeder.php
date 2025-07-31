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
            'subdomain' => 'accel.waskamirealty.online',
            'image' => 'accel',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelHr',
            'subdomain' => 'hr.waskamirealty.online',
            'image' => 'accel-hr',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelStock',
            'subdomain' => 'stock.waskamirealty.online',
            'image' => 'accel-stock',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelBuild',
            'subdomain' => 'build.waskamirealty.online',
            'image' => 'accel-build',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelTask',
            'subdomain' => 'task.waskamirealty.online',
            'image' => 'accel-task',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelDocs',
            'subdomain' => 'docs.waskamirealty.online',
            'image' => 'accel-docs',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'AccelRef',
            'subdomain' => 'ref.waskamirealty.online',
            'image' => 'accel-ref',
            'order_number' => $app_no++
        ]);
    }
}
