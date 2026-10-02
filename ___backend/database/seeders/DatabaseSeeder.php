<?php

namespace Database\Seeders;

use App\Models\MatchModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use App\Services\MatchResultsService;
use Illuminate\Database\Seeder;

/**
 * Demo data for local development:
 *   owner     demo@soccer.test   / password
 *   sub-user  scorer@soccer.test / password
 */
class DatabaseSeeder extends Seeder
{
    public function run(MatchResultsService $results): void
    {
        $owner = UserModel::factory()->create(['email' => 'demo@soccer.test', 'firstname' => 'Demo', 'no_of_cups' => 1, 'no_of_leagues' => 1]);
        SubUserModel::factory()->create(['email' => 'scorer@soccer.test', 'user_id' => $owner->user_id]);

        // A league where everybody plays everybody once.
        $league = TournamentModel::factory()->create(['user_id' => $owner->user_id, 'tour_title' => 'Sunday League']);
        $teams = TeamModel::factory()->count(4)->create(['tour_id' => $league->tour_id]);

        foreach ($teams as $i => $home) {
            foreach ($teams->slice($i + 1) as $away) {
                $match = MatchModel::factory()->between($home, $away)->create();
                if (fake()->boolean(60)) {
                    $results->saveResult($match, fake()->numberBetween(0, 4), fake()->numberBetween(0, 4));
                }
            }
        }

        // A cup with two groups and a knock-out tie decided on penalties.
        $cup = TournamentModel::factory()->cup()->create(['user_id' => $owner->user_id, 'tour_title' => 'Summer Cup']);
        $groups = collect(['A', 'B'])->mapWithKeys(fn ($group) => [
            $group => TeamModel::factory()->count(3)->create(['tour_id' => $cup->tour_id, 'group_in' => $group]),
        ]);

        foreach ($groups as $group) {
            [$a, $b, $c] = $group->all();
            foreach ([[$a, $b], [$b, $c], [$c, $a]] as [$home, $away]) {
                $match = MatchModel::factory()->between($home, $away, MatchModel::GROUP_STAGE)->create();
                $results->saveResult($match, fake()->numberBetween(0, 3), fake()->numberBetween(0, 3));
            }
        }

        $semi = MatchModel::factory()->between($groups['A'][0], $groups['B'][1], 'Semi_Final')->create();
        $results->saveResult($semi, 1, 1, 4, 3);
        MatchModel::factory()->between($groups['B'][0], $groups['A'][1], 'Semi_Final')->create();
    }
}
