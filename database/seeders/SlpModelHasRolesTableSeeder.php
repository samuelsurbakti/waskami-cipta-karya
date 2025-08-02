<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlpModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('slp_model_has_roles')->delete();
        
        \DB::table('slp_model_has_roles')->insert(array (
            0 => 
            array (
                'role_id' => '01985a43-2375-73e6-8989-1390706e8824',
                'model_type' => 'App\\Models\\User',
                'model_uuid' => '01985a43-259e-72b9-a073-6073baf610d2',
            ),
        ));
        
        
    }
}