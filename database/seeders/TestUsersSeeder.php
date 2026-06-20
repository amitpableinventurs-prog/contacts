<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@test.com',
                'role'     => Roles::SUPER_ADMIN,
            ],
            [
                'name'     => 'Admin User',
                'email'    => 'admin@test.com',
                'role'     => Roles::ADMIN,
            ],
            [
                'name'     => 'Manager User',
                'email'    => 'manager@test.com',
                'role'     => Roles::MANAGER,
            ],
            [
                'name'     => 'Clerk User',
                'email'    => 'clerk@test.com',
                'role'     => Roles::CLERK,
            ],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name'      => $account['name'],
                    'password'  => Hash::make('Password@123'),
                    'role'      => $account['role'],
                    'is_active' => true,
                ]
            );
        }
    }
}
