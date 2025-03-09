<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user-create',
            'user-edit',
            'user-delete',
            'role-create',
            'role-edit',
            'role-delete',
        ];
        foreach($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $user = Role::firstOrCreate(['name' => 'User']);

        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions(['user-create','user-edit']);
        $user->syncPermissions(['user-create']);
        $superAdminRoleAssign = \App\Models\User::where('email','super-admin@gmail.com')->first();
        if($superAdminRoleAssign){
            $superAdminRoleAssign->assignRole('SuperAdmin');
        }

    }
}
