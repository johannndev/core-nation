<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class permissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'superadmin']);

        $permission = Permission::all()->pluck('name')->toArray();

        $role->syncPermissions($permission);

        $user = User::find(1);

        $user->syncRoles('superadmin');

        
    }
}
