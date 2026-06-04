<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@printing.gov.mw'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'is_staff' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('super_admin');

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'is_staff' => false,
                'email_verified_at' => now(),
            ]
        )->assignRole('customer');

        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser && ! $testUser->customerProfile) {
            CustomerProfile::create([
                'user_id' => $testUser->id,
                'type' => 'individual',
            ]);
        }
    }
}
