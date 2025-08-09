<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(AppSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(PermissionSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(WorkerTypeSeeder::class);
        $this->call(ActivityLogTableSeeder::class);
        $this->call(HrWorkersTableSeeder::class);
        $this->call(HrWorkerTypesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SysAppsTableSeeder::class);
        $this->call(SysMenusTableSeeder::class);
        $this->call(SlpRolesTableSeeder::class);
        $this->call(SlpPermissionsTableSeeder::class);
        $this->call(SlpModelHasPermissionsTableSeeder::class);
        $this->call(SlpModelHasRolesTableSeeder::class);
        $this->call(SlpRoleHasPermissionsTableSeeder::class);
        $this->call(HrContractsTableSeeder::class);
        $this->call(HrContractTypesTableSeeder::class);
        $this->call(HrTeamsTableSeeder::class);
        $this->call(HrTeamMembersTableSeeder::class);
        $this->call(HrPayrollsTableSeeder::class);
        $this->call(HrAttendancesTableSeeder::class);
    }
}
