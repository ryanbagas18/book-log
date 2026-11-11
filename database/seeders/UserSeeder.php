<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn('WARNING: seeded users use the dev-only password "password". Do not use in production.');

        $admin = User::factory()->create([
            'name' => 'Ryan',
            'email' => 'admin@ryanlibrary.test',
            'password' => 'password',
            'phone' => '+6281234567890',
            'address' => 'Jakarta, Indonesia',
        ]);
        $admin->role = 'admin';
        $admin->save();

        User::factory()->create([
            'name' => 'Complete Customer',
            'email' => 'customer.complete@example.test',
            'password' => 'password',
            'phone' => '+628987654321',
            'address' => 'Bandung, Indonesia',
        ]);

        User::factory()->create([
            'name' => 'Incomplete Customer',
            'email' => 'customer.incomplete@example.test',
            'password' => 'password',
        ]);
    }
}
