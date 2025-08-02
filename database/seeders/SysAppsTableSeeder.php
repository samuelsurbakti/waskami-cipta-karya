<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SysAppsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sys_apps')->delete();
        
        \DB::table('sys_apps')->insert(array (
            0 => 
            array (
                'id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'name' => 'Accel',
                'subdomain' => 'accel.waskamirealty.online',
                'image' => 'accel',
                'order_number' => '0',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'name' => 'AccelHr',
                'subdomain' => 'hr.waskamirealty.online',
                'image' => 'accel-hr',
                'order_number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => '01985a43-234e-73ad-aad5-a8c4eb8cd899',
                'name' => 'AccelStock',
                'subdomain' => 'stock.waskamirealty.online',
                'image' => 'accel-stock',
                'order_number' => '2',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => '01985a43-234f-7223-89c9-5d2a3ee2f1ad',
                'name' => 'AccelBuild',
                'subdomain' => 'build.waskamirealty.online',
                'image' => 'accel-build',
                'order_number' => '3',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => '01985a43-2351-7268-9504-a78f23bdf157',
                'name' => 'AccelTask',
                'subdomain' => 'task.waskamirealty.online',
                'image' => 'accel-task',
                'order_number' => '4',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => '01985a43-2353-7318-8f79-c25d13bd537d',
                'name' => 'AccelDocs',
                'subdomain' => 'docs.waskamirealty.online',
                'image' => 'accel-docs',
                'order_number' => '5',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => '01985a43-2355-728c-a76e-c1e0ea270064',
                'name' => 'AccelRef',
                'subdomain' => 'ref.waskamirealty.online',
                'image' => 'accel-ref',
                'order_number' => '6',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}