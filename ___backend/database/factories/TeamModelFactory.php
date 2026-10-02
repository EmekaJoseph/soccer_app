<?php

namespace Database\Factories;

use App\Models\Standings_CupModel;
use App\Models\Standings_LeagueModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Creates the team together with its (empty) standings row, as the API does.
 *
 * @extends Factory<TeamModel>
 */
class TeamModelFactory extends Factory
{
    protected $model = TeamModel::class;

    public function definition(): array
    {
        return [
            'team_name' => fake()->unique()->lastName().' FC',
            'tour_id' => TournamentModel::factory(),
            'manager' => fake()->name(),
            'team_color' => fake()->hexColor(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TeamModel $team) {
            $tournament = TournamentModel::findOrFail($team->tour_id);
            $model = $tournament->isCup() ? Standings_CupModel::class : Standings_LeagueModel::class;

            $model::create([
                'team_id' => $team->team_id,
                'tour_id' => $team->tour_id,
                'group_in' => $team->group_in,
            ]);
        });
    }
}
