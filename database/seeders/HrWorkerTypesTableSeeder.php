<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HrWorkerTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('hr_worker_types')->delete();
        
        \DB::table('hr_worker_types')->insert(array (
            0 => 
            array (
                'id' => '01985a43-275a-7367-8687-206e5274be6c',
                'name' => 'tukang',
                'created_at' => '2025-07-30 07:36:47',
                'updated_at' => '2025-07-30 07:36:47',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => '01985a43-275c-7321-bdf5-f333e41d1775',
                'name' => 'kernet',
                'created_at' => '2025-07-30 07:36:47',
                'updated_at' => '2025-07-30 07:36:47',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => '01985a43-275e-701a-8f20-f32c0b70b1af',
                'name' => 'pegawai',
                'created_at' => '2025-07-30 07:36:47',
                'updated_at' => '2025-07-30 07:36:47',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}