<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(UpdateUserHasParentSeeder::class);

        // Admin::create([
        //     'fullname' => 'moein fadakar',
        //     'email' => 'moein@gmail.com',
        //     "password" => Hash::make("moeinfadakar"),
        //     "type" => "manager"
        // ]);
    }
}
