<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin>
 */
class adminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'fullname' => "moein fadakar",
            'email' => "moeinfadakar3@gmail.com",
            'password' =>  Hash::make('moeinfadakar'),
            'type' => "manager",
        ];
    }
}
