<?php

namespace Database\Factories;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserModel>
 */
class UserModelFactory extends Factory
{
    protected $model = UserModel::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'role' => 'admin',
        ];
    }
}
