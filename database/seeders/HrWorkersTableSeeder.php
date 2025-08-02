<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HrWorkersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('hr_workers')->delete();
        
        \DB::table('hr_workers')->insert(array (
            0 => 
            array (
                'id' => '01985eae-5670-7081-948f-ac2fa4095530',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Surya Edi Syahputra',
                'phone' => '081362230796',
                'whatsapp' => '081362230796',
                'address' => '',
                'is_active' => '1',
                'created_at' => '2025-07-31 04:12:20',
                'updated_at' => '2025-08-01 15:54:40',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => '01985f8f-3e4c-71cb-a8ee-7ca2c9b0be21',
                'type_id' => '01985a43-275c-7321-bdf5-f333e41d1775',
                'name' => 'Deman Agus',
                'phone' => '',
                'whatsapp' => '',
                'address' => '',
                'is_active' => '1',
                'created_at' => '2025-07-31 08:17:59',
                'updated_at' => '2025-08-01 15:52:18',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => '01985fda-d18b-714f-ad20-e32e6ad89c8c',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Suwito',
                'phone' => '081362160981',
                'whatsapp' => NULL,
                'address' => NULL,
                'is_active' => '1',
                'created_at' => '2025-07-31 09:40:32',
                'updated_at' => '2025-07-31 09:40:32',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => '01986018-69da-73bf-9c13-a389d6fc51c0',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Harianto',
                'phone' => NULL,
                'whatsapp' => NULL,
                'address' => NULL,
                'is_active' => '1',
                'created_at' => '2025-07-31 10:47:49',
                'updated_at' => '2025-07-31 10:47:49',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => '01986019-2c80-7051-bad0-4eb398c792ba',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Pairan',
                'phone' => '085361559253',
                'whatsapp' => '085361559253',
                'address' => NULL,
                'is_active' => '1',
                'created_at' => '2025-07-31 10:48:39',
                'updated_at' => '2025-07-31 10:48:39',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => '01986273-3a6a-7326-ad07-3d114cdcb45b',
                'type_id' => '01985a43-275c-7321-bdf5-f333e41d1775',
                'name' => 'Contoh 1',
                'phone' => NULL,
                'whatsapp' => NULL,
                'address' => NULL,
                'is_active' => '1',
                'created_at' => '2025-07-31 21:46:15',
                'updated_at' => '2025-07-31 22:26:32',
                'deleted_at' => '2025-07-31 22:26:32',
            ),
            6 => 
            array (
                'id' => '01986273-c3f6-7360-923d-bfbc43901c3b',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Suwito',
                'phone' => NULL,
                'whatsapp' => '083851560539',
                'address' => 'Gg. Benteng',
                'is_active' => '1',
                'created_at' => '2025-07-31 21:46:50',
                'updated_at' => '2025-08-01 01:29:54',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => '0198632f-a3c1-7353-9077-8bd1ff520c9f',
                'type_id' => '01985a43-275c-7321-bdf5-f333e41d1775',
                'name' => 'Mahardika',
                'phone' => '083161119778',
                'whatsapp' => '083161119778',
                'address' => 'Jl. Bangun Sari, Kel. Kedai Durian',
                'is_active' => '1',
                'created_at' => '2025-08-01 01:12:03',
                'updated_at' => '2025-08-01 01:12:03',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => '01986341-caa0-7344-8cb0-d9180c64da02',
                'type_id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'Surya',
                'phone' => NULL,
                'whatsapp' => NULL,
                'address' => 'Jl. Pintu Air 1',
                'is_active' => '1',
                'created_at' => '2025-08-01 01:31:52',
                'updated_at' => '2025-08-01 01:31:52',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}