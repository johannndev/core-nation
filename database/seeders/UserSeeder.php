<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nama role yang ingin dipakai
        $roleName = 'superadmin'; // bisa diganti sesuai kebutuhan
        $role = $role = Role::where('name',$roleName)->first();

        // Buat user baru
        $user = new User();
        $user->username = 'superadmin';
        $user->location_id = 0; // contoh city/location
        $user->role_id = $role->id;

        // generate password
        
        $user->password = Hash::make('12345678');
        $user->active = 1;

        if (!$user->save()) {
            throw new \Exception('Cannot save user', 1);
        }

        // Sync role
        $user->syncRoles($roleName);

        // Optional: tampilkan info password di console
        $this->command->info("User created: {$user->username} | Password: {12345678}");
    }
}
