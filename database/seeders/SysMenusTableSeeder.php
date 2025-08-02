<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SysMenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sys_menus')->delete();
        
        \DB::table('sys_menus')->insert(array (
            0 => 
            array (
                'id' => '01985a43-2359-7099-a6bf-63abc0cc6f30',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'title' => 'Portal',
                'icon' => 'bx bx-grid-alt',
                'url' => 'gate',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => '01985a43-235b-7082-97a9-d574a5e49744',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'title' => 'Akun',
                'icon' => 'bx bxs-user-account',
                'url' => 'account',
                'order_number' => '2',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'title' => 'Sistem',
                'icon' => 'bx bx-cog',
                'url' => 'system',
                'order_number' => '3',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'title' => 'Otorisasi',
                'icon' => 'bx bx-check-shield',
                'url' => 'authorization',
                'order_number' => '4',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => '01985a43-2360-71ea-9373-af30ab0688ba',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'title' => 'Pekerja',
                'icon' => 'bx bx-group',
                'url' => 'worker',
                'order_number' => '2',
                'parent' => NULL,
                'member_of' => 'Manajemen',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => '01985a43-2364-727d-8495-551c5630b022',
                'app_id' => '01985a43-234e-73ad-aad5-a8c4eb8cd899',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => '01985a43-2367-7337-acc3-33d87ac99a47',
                'app_id' => '01985a43-234f-7223-89c9-5d2a3ee2f1ad',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => '01985a43-2369-72c9-9172-cebaeb702600',
                'app_id' => '01985a43-2351-7268-9504-a78f23bdf157',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => '01985a43-236c-71a6-8a25-f22e070edcd5',
                'app_id' => '01985a43-2353-7318-8f79-c25d13bd537d',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => '01985a43-236e-730e-b1fb-4135904b5fd6',
                'app_id' => '01985a43-2355-728c-a76e-c1e0ea270064',
                'title' => 'Beranda',
                'icon' => 'bx bx-home',
                'url' => 'home',
                'order_number' => '1',
                'parent' => NULL,
                'member_of' => NULL,
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'title' => 'Presensi',
                'icon' => 'bx bx-calendar-check',
                'url' => 'attendance',
                'order_number' => '3',
                'parent' => NULL,
                'member_of' => 'Manajemen',
                'created_at' => '2025-08-01 16:12:02',
                'updated_at' => '2025-08-01 16:12:16',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}