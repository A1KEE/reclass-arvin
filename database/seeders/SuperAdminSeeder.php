<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // create role if not exists
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // create user
        $user = User::updateOrCreate(
            ['email' => 'ikejoseph.lumaad@deped.gov.ph'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'must_change_password' => 0
            ]
        );

        // assign role
        $user->syncRoles([$role]);
    }
}