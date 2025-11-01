<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $users = [
                [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@elangperdana.com',
                    'password' => Hash::make('password'),
                    'role' => 'superadmin',
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Faizal Ramadhan',
                    'email' => 'admin@elangperdana.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Mas Abbas',
                    'email' => 'user@elangperdana.com',
                    'password' => Hash::make('user123'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ],
            ];

            foreach ($users as $userData) {
                $user = User::where('email', $userData['email'])->first();
                
                if ($user) {
                    // Update user yang sudah ada
                    $user->update($userData);
                    $this->command->info("Updated user: {$userData['email']}");
                } else {
                    // Buat user baru
                    User::create($userData);
                    $this->command->info("Created user: {$userData['email']}");
                }
            }

            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding users: " . $e->getMessage());
        }
    }
}