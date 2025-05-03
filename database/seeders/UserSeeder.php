<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Simple User',
            'email' => 'simple-user@example.com',
            'password' => Hash::make('simple-user@example.com'),
            'is_admin' => false,
        ]);
        User::factory()->count(5)->create();
    }
}
