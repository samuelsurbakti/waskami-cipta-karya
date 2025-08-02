<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => '01985a43-259e-72b9-a073-6073baf610d2',
                'name' => 'Sammy',
                'username' => 'sammy',
                'email' => 'sammysurbakti@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$fhpVujHxRobqgIn.xEVat.BPSexQg91JGVXH0pOmtI.yGa/VtjT5.',
                'avatar' => 'aa969cb2d09ce43fdd6c19ffc6d74cc1.png',
                'remember_token' => 'jPCcTTUZZ1b8n5nRqKBS7wMlVSBhq8AxocS5Gzkzs4coYlil64S1TkIi57HB',
                'account_status' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}