<?php

namespace Database\Factories;

use App\Models\SubUserModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubUserModel>
 */
class SubUserModelFactory extends Factory
{
    protected $model = SubUserModel::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'user_id' => UserModel::factory(),
            'role' => 'sub',
            'is_active' => '1',
            'created_at' => now(),
        ];
    }
}
