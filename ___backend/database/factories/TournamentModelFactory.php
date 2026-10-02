<?php

namespace Database\Factories;

use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TournamentModel>
 */
class TournamentModelFactory extends Factory
{
    protected $model = TournamentModel::class;

    public function definition(): array
    {
        return [
            'tour_title' => fake()->unique()->city().' Cup',
            'tour_type' => TournamentModel::TYPE_LEAGUE,
            'tour_desc' => fake()->sentence(),
            'user_id' => UserModel::factory(),
        ];
    }

    public function cup(): static
    {
        return $this->state(['tour_type' => TournamentModel::TYPE_CUP]);
    }
}
