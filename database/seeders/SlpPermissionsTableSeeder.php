<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlpPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('slp_permissions')->delete();
        
        \DB::table('slp_permissions')->insert(array (
            0 => 
            array (
                'uuid' => '01985a43-237d-7055-8e94-484ae59fc5d1',
                'type' => 'App',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => NULL,
                'name' => 'Accel',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            1 => 
            array (
                'uuid' => '01985a43-2383-719e-ae51-3afcc50cfd5d',
                'type' => 'Menu',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-2359-7099-a6bf-63abc0cc6f30',
                'name' => 'Accel - Portal',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            2 => 
            array (
                'uuid' => '01985a43-2386-72c9-9f5a-60ceb326c696',
                'type' => 'Menu',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235b-7082-97a9-d574a5e49744',
                'name' => 'Accel - Akun',
                'guard_name' => 'web',
                'number' => '2',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            3 => 
            array (
                'uuid' => '01985a43-2389-72a9-9582-aef9830befac',
                'type' => 'Menu',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem',
                'guard_name' => 'web',
                'number' => '3',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            4 => 
            array (
                'uuid' => '01985a43-238c-7349-bb43-f7d4039edc59',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Aplikasi - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '11',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            5 => 
            array (
                'uuid' => '01985a43-2390-725c-aaf2-96e271fd3dab',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Aplikasi - Menambah Data',
                'guard_name' => 'web',
                'number' => '12',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            6 => 
            array (
                'uuid' => '01985a43-2393-736a-a09f-c4bfdc3b4e68',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Aplikasi - Mengubah Data',
                'guard_name' => 'web',
                'number' => '13',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            7 => 
            array (
                'uuid' => '01985a43-2396-726a-83e4-f4a3f19f60df',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Menu - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '21',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            8 => 
            array (
                'uuid' => '01985a43-239a-7238-a59f-bc1b0466cf0b',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Menu - Menambah Data',
                'guard_name' => 'web',
                'number' => '22',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            9 => 
            array (
                'uuid' => '01985a43-239e-720e-bb92-8e87c9a3fc04',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Menu - Mengubah Data',
                'guard_name' => 'web',
                'number' => '23',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            10 => 
            array (
                'uuid' => '01985a43-23a4-714e-bf02-8c4ab635b4b2',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235c-71c4-a5fa-3da410cee4f7',
                'name' => 'Accel - Sistem - Menu - Menghapus Data',
                'guard_name' => 'web',
                'number' => '24',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            11 => 
            array (
                'uuid' => '01985a43-23a9-739f-a227-b93f2b63307b',
                'type' => 'Menu',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi',
                'guard_name' => 'web',
                'number' => '4',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            12 => 
            array (
                'uuid' => '01985a43-23ae-73c1-8e19-8ce90bace26d',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Mengelola Otoritas',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            13 => 
            array (
                'uuid' => '01985a43-23b3-726e-a9c9-76317ec99e72',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Peran - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '11',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            14 => 
            array (
                'uuid' => '01985a43-23b7-7075-a8e9-6c266fb79126',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Peran - Menambah Data',
                'guard_name' => 'web',
                'number' => '12',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            15 => 
            array (
                'uuid' => '01985a43-23bb-7048-9f1b-afecc25a278e',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Peran - Mengubah Data',
                'guard_name' => 'web',
                'number' => '13',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            16 => 
            array (
                'uuid' => '01985a43-23c0-70f3-8ddf-aa12cbaf7869',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Izin - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '21',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            17 => 
            array (
                'uuid' => '01985a43-23c4-71de-8c42-c18b86d4d778',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Izin - Menambah Data',
                'guard_name' => 'web',
                'number' => '22',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            18 => 
            array (
                'uuid' => '01985a43-23ca-71e8-8729-92cd33cb1aba',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Izin - Mengubah Data',
                'guard_name' => 'web',
                'number' => '23',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            19 => 
            array (
                'uuid' => '01985a43-23d1-700f-b6ba-69bf12cdd87b',
                'type' => 'Permission',
                'app_id' => '01985a43-233f-70e5-859a-6f980e90df00',
                'menu_id' => '01985a43-235e-72ca-92ed-623ce4af7db1',
                'name' => 'Accel - Otorisasi - Izin - Menghapus Data',
                'guard_name' => 'web',
                'number' => '24',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            20 => 
            array (
                'uuid' => '01985a43-23d5-721d-99dd-4c57fe2e276d',
                'type' => 'App',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => NULL,
                'name' => 'AccelHr',
                'guard_name' => 'web',
                'number' => '2',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            21 => 
            array (
                'uuid' => '01985a43-23db-72a4-af4f-89785a02d5a8',
                'type' => 'Menu',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2360-71ea-9373-af30ab0688ba',
                'name' => 'AccelHr - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            22 => 
            array (
                'uuid' => '01985a43-23df-71df-8455-d1990f2e8bb2',
                'type' => 'Menu',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja',
                'guard_name' => 'web',
                'number' => '2',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            23 => 
            array (
                'uuid' => '01985a43-23e4-7102-8f6c-df780555f1c5',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '11',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            24 => 
            array (
                'uuid' => '01985a43-23e8-7111-a45e-8050385661b4',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Melihat Data',
                'guard_name' => 'web',
                'number' => '12',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            25 => 
            array (
                'uuid' => '01985a43-23ed-731a-bc9b-eefa8b75a42d',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Menambah Data',
                'guard_name' => 'web',
                'number' => '13',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            26 => 
            array (
                'uuid' => '01985a43-23f1-7384-949a-a4c38d6e17e8',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Mengubah Data',
                'guard_name' => 'web',
                'number' => '14',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            27 => 
            array (
                'uuid' => '01985a43-23f6-7190-add0-3778bb18cd0c',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Menghapus Data',
                'guard_name' => 'web',
                'number' => '15',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            28 => 
            array (
                'uuid' => '01985a43-23fb-73a1-b64e-c4826a740ef3',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Jenis - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '21',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            29 => 
            array (
                'uuid' => '01985a43-2400-7200-a22c-8d90fd9d2f28',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Jenis - Menambah Data',
                'guard_name' => 'web',
                'number' => '22',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            30 => 
            array (
                'uuid' => '01985a43-2405-719c-9ca1-832ae473e4c0',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Jenis - Mengubah Data',
                'guard_name' => 'web',
                'number' => '23',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            31 => 
            array (
                'uuid' => '01985a43-240b-735b-85ee-f309953d7251',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01985a43-2362-70d1-93d0-8df50b99a5ca',
                'name' => 'AccelHr - Pekerja - Jenis - Menghapus Data',
                'guard_name' => 'web',
                'number' => '24',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            32 => 
            array (
                'uuid' => '01985a43-2410-7132-af18-2fe372c9ada4',
                'type' => 'App',
                'app_id' => '01985a43-234e-73ad-aad5-a8c4eb8cd899',
                'menu_id' => NULL,
                'name' => 'AccelStock',
                'guard_name' => 'web',
                'number' => '3',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            33 => 
            array (
                'uuid' => '01985a43-2416-729f-98b6-695bb505b664',
                'type' => 'Menu',
                'app_id' => '01985a43-234e-73ad-aad5-a8c4eb8cd899',
                'menu_id' => '01985a43-2364-727d-8495-551c5630b022',
                'name' => 'AccelStock - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            34 => 
            array (
                'uuid' => '01985a43-241b-716e-8f3a-7214b7ef3716',
                'type' => 'App',
                'app_id' => '01985a43-234f-7223-89c9-5d2a3ee2f1ad',
                'menu_id' => NULL,
                'name' => 'AccelBuild',
                'guard_name' => 'web',
                'number' => '4',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            35 => 
            array (
                'uuid' => '01985a43-2421-7185-b5be-870da59ae0eb',
                'type' => 'Menu',
                'app_id' => '01985a43-234f-7223-89c9-5d2a3ee2f1ad',
                'menu_id' => '01985a43-2367-7337-acc3-33d87ac99a47',
                'name' => 'AccelBuild - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            36 => 
            array (
                'uuid' => '01985a43-2427-71dc-9e32-66ececf487c1',
                'type' => 'App',
                'app_id' => '01985a43-2351-7268-9504-a78f23bdf157',
                'menu_id' => NULL,
                'name' => 'AccelTask',
                'guard_name' => 'web',
                'number' => '5',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            37 => 
            array (
                'uuid' => '01985a43-2430-73ba-acb7-cb813a13c608',
                'type' => 'Menu',
                'app_id' => '01985a43-2351-7268-9504-a78f23bdf157',
                'menu_id' => '01985a43-2369-72c9-9172-cebaeb702600',
                'name' => 'AccelTask - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            38 => 
            array (
                'uuid' => '01985a43-2437-738f-bef9-d25b321c9f50',
                'type' => 'App',
                'app_id' => '01985a43-2353-7318-8f79-c25d13bd537d',
                'menu_id' => NULL,
                'name' => 'AccelDocs',
                'guard_name' => 'web',
                'number' => '6',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            39 => 
            array (
                'uuid' => '01985a43-243e-721f-9d93-37ca217d1b1e',
                'type' => 'Menu',
                'app_id' => '01985a43-2353-7318-8f79-c25d13bd537d',
                'menu_id' => '01985a43-236c-71a6-8a25-f22e070edcd5',
                'name' => 'AccelDocs - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            40 => 
            array (
                'uuid' => '01985a43-2443-7312-8cdc-c4eaeefc2168',
                'type' => 'App',
                'app_id' => '01985a43-2355-728c-a76e-c1e0ea270064',
                'menu_id' => NULL,
                'name' => 'AccelRef',
                'guard_name' => 'web',
                'number' => '7',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            41 => 
            array (
                'uuid' => '01985a43-244a-7399-a638-1bd76c1fd613',
                'type' => 'Menu',
                'app_id' => '01985a43-2355-728c-a76e-c1e0ea270064',
                'menu_id' => '01985a43-236e-730e-b1fb-4135904b5fd6',
                'name' => 'AccelRef - Beranda',
                'guard_name' => 'web',
                'number' => '1',
                'created_at' => '2025-07-30 07:36:46',
                'updated_at' => '2025-07-30 07:36:46',
            ),
            42 => 
            array (
                'uuid' => '01986668-a879-7310-9298-6b7814bf6fbb',
                'type' => 'Menu',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'name' => 'AccelHr - Presensi',
                'guard_name' => 'web',
                'number' => '3',
                'created_at' => '2025-08-01 16:13:11',
                'updated_at' => '2025-08-01 16:13:11',
            ),
            43 => 
            array (
                'uuid' => '01986669-ab55-707e-8ef7-0aafd65844d8',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'name' => 'AccelHr - Presensi - Melihat Daftar Data',
                'guard_name' => 'web',
                'number' => '11',
                'created_at' => '2025-08-01 16:14:17',
                'updated_at' => '2025-08-01 16:14:17',
            ),
            44 => 
            array (
                'uuid' => '0198666a-8f4e-73f9-ab9d-96ce1e57c004',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'name' => 'AccelHr - Presensi - Menambah Data',
                'guard_name' => 'web',
                'number' => '12',
                'created_at' => '2025-08-01 16:15:16',
                'updated_at' => '2025-08-01 16:15:16',
            ),
            45 => 
            array (
                'uuid' => '0198666a-ee3a-7338-9cf6-b8373ebc5b3f',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'name' => 'AccelHr - Presensi - Mengubah Data',
                'guard_name' => 'web',
                'number' => '13',
                'created_at' => '2025-08-01 16:15:40',
                'updated_at' => '2025-08-01 16:15:40',
            ),
            46 => 
            array (
                'uuid' => '0198666b-2fa0-7243-b614-3467a784bd35',
                'type' => 'Permission',
                'app_id' => '01985a43-234c-7148-9d8c-85d0eef33b70',
                'menu_id' => '01986667-9b78-73c2-b5a1-320ac0030b75',
                'name' => 'AccelHr - Presensi - Menghapus Data',
                'guard_name' => 'web',
                'number' => '14',
                'created_at' => '2025-08-01 16:15:57',
                'updated_at' => '2025-08-01 16:15:57',
            ),
        ));
        
        
    }
}