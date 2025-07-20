<?php

namespace Database\Seeders;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use App\Models\SLP\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $app_number = 1;
        foreach (App::orderBy('order_number')->get() as $app) {
            Permission::create([
                'type' => 'App',
                'app_id' => $app->id,
                'menu_id' => null,
                'name' => $app->name,
                'guard_name' => 'web',
                'number' => $app_number++,
            ]);

            $menu_number = 1;

            foreach (Menu::where('app_id', $app->id)->orderBy('order_number')->get() as $menu) {
                Permission::create([
                    'type' => 'Menu',
                    'app_id' => $app->id,
                    'menu_id' => $menu->id,
                    'name' => $app->name.' | '.$menu->title,
                    'guard_name' => 'web',
                    'number' => $menu_number++,
                ]);
            }
        }
    }
}
