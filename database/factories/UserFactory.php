<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'username' => Str::random(4), 
            'password' => Hash::make('123'), 
            'is_admin' => $this->faker->boolean(), 
            'remember_token' => Str::random(10),
        ];
    }
}
