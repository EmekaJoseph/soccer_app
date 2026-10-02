<?php

namespace Database\Factories;

use App\Models\MatchModel;
use App\Models\TeamModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchModel>
 */
class MatchModelFactory extends Factory
{
    protected $model = MatchModel::class;

    public function definition(): array
    {
        return [
            'venue' => fake()->streetName().' Stadium',
            'kick_off' => now()->addDays(fake()->numberBetween(1, 30))->startOfHour()->toIso8601ZuluString('millisecond'),
            'match_stage' => null,
            'created' => now()->toDateTimeString(),
        ];
    }

    public function between(TeamModel $home, TeamModel $away, ?string $stage = null): static
    {
        return $this->state([
            'tour_id' => $home->tour_id,
            'home_team' => $home->team_id,
            'away_team' => $away->team_id,
            'match_stage' => $stage,
        ]);
    }
}
