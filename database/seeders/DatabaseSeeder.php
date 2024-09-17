<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAdmin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        UserAdmin::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
