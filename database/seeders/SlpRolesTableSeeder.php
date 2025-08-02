<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlpRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('slp_roles')->delete();
        
        \DB::table('slp_roles')->insert(array (
            0 => 
            array (
                'uuid' => '01985a43-2375-73e6-8989-1390706e8824',
                'name' => 'Developer',
                'guard_name' => 'web',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            1 => 
            array (
                'uuid' => '01985a43-2376-7327-8f6e-a1af8b532b38',
                'name' => 'Direksi',
                'guard_name' => 'web',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
        ));
        
        
    }
}