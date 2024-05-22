<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

//         User::factory()->create([
//             'name' => 'Admin User',
//             'email' => 'test@example.com',
//             'password' => Hash::make('password')
//         ]);

        $user =  User::find(1);
        $user->update(['password' => Hash::make('password')]);
    }
}
