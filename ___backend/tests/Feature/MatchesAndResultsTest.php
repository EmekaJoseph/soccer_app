<?php

namespace Tests\Feature;

use App\Models\MatchModel;
use App\Models\ResultModel;
use App\Models\Standings_CupModel;
use App\Models\Standings_LeagueModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchesAndResultsTest extends TestCase
{
    use RefreshDatabase;

    private TournamentModel $league;

    private TeamModel $home;

    private TeamModel $away;

    protected function setUp(): void
    {
        parent::setUp();
        $owner = $this->signIn();
        $this->league = TournamentModel::factory()->create(['user_id' => $owner->user_id]);
        [$this->home, $this->away] = TeamModel::factory()->count(2)->create(['tour_id' => $this->league->tour_id])->all();
    }

    private function standing(TeamModel $team): Standings_LeagueModel
    {
        return Standings_LeagueModel::firstWhere('team_id', $team->team_id);
    }

    public function test_matches_are_scheduled_validated_edited_and_listed(): void
    {
        $payload = [
            'tour_id' => $this->league->tour_id,
            'homeTeam' => $this->home->team_id,
            'awayTeam' => $this->away->team_id,
            'venue' => 'Main pitch',
            'kick_off' => '2030-05-01T15:00:00.000Z',
        ];

        $id = $this->postJson('/api/matches', $payload)->assertCreated()
            ->assertJsonPath('home_team.team_name', $this->home->team_name)
            ->json('match_id');

        $this->postJson('/api/matches', $payload)->assertConflict();
        $this->postJson('/api/matches', [...$payload, 'awayTeam' => $this->home->team_id])->assertJsonValidationErrors('awayTeam');
        $this->postJson('/api/matches', [...$payload, 'awayTeam' => TeamModel::factory()->create()->team_id])
            ->assertJsonValidationErrors('homeTeam');

        $this->putJson("/api/matches/{$id}", ['venue' => 'Pitch 2', 'kick_off' => '2030-05-02T10:00:00.000Z'])
            ->assertOk()->assertJson(['venue' => 'Pitch 2', 'kick_off' => '2030-05-02T10:00:00.000Z']);

        $this->getJson("/api/tournaments/{$this->league->tour_id}/matches")->assertOk()->assertJsonCount(1);
        $this->getJson("/api/view/tournaments/{$this->league->tour_id}/matches")->assertOk()->assertJsonCount(1);

        $this->deleteJson("/api/matches/{$id}")->assertOk();
        $this->assertDatabaseCount('tbl_matches', 0);
    }

    public function test_cup_matches_need_a_stage(): void
    {
        $cup = TournamentModel::factory()->cup()->create(['user_id' => $this->league->user_id]);
        [$a, $b] = TeamModel::factory()->count(2)->create(['tour_id' => $cup->tour_id, 'group_in' => 'A'])->all();

        $this->postJson('/api/matches', [
            'tour_id' => $cup->tour_id, 'homeTeam' => $a->team_id, 'awayTeam' => $b->team_id,
            'venue' => 'V', 'kick_off' => '2030-01-01T10:00:00Z',
        ])->assertJsonValidationErrors('match_stage');
    }

    public function test_league_results_update_standings_and_undo_reverses_them_exactly(): void
    {
        $match = MatchModel::factory()->between($this->home, $this->away)->create();

        $resultId = $this->postJson('/api/results', ['match_id' => $match->match_id, 'homeTeam_score' => 3, 'awayTeam_score' => 1])
            ->assertCreated()->json('result_id');

        $home = $this->standing($this->home);
        $away = $this->standing($this->away);
        $this->assertSame([1, 1, 0, 0, 2, 3], [$home->played, $home->won, $home->draw, $home->lose, $home->goal_diff, $home->points]);
        $this->assertSame([1, 0, 0, 1, -2, 0], [$away->played, $away->won, $away->draw, $away->lose, $away->goal_diff, $away->points]);
        $this->assertSame(1, $this->home->fresh()->match_played);

        // A match can only have one result.
        $this->postJson('/api/results', ['match_id' => $match->match_id, 'homeTeam_score' => 0, 'awayTeam_score' => 0])->assertConflict();

        // The match has left the public fixtures list and shows up in results.
        $this->getJson("/api/view/tournaments/{$this->league->tour_id}/matches")->assertJsonCount(0);
        $this->getJson("/api/view/tournaments/{$this->league->tour_id}/results")
            ->assertJsonPath('0.winner', $this->home->team_id)
            ->assertJsonPath('0.home_name', $this->home->team_name);

        // Cannot delete a played match.
        $this->deleteJson("/api/matches/{$match->match_id}")->assertConflict();

        $this->deleteJson("/api/results/{$resultId}")->assertOk();

        $home = $this->standing($this->home);
        $this->assertSame([0, 0, 0, 0, 0, 0], [$home->played, $home->won, $home->draw, $home->lose, $home->goal_diff, $home->points]);
        $this->assertSame(0, $this->standing($this->away)->lose);
        $this->assertSame(0, $this->home->fresh()->match_played);
        $this->assertDatabaseCount('tbl_results', 0);
    }

    public function test_draw_gives_a_point_each_and_standings_are_sorted(): void
    {
        $third = TeamModel::factory()->create(['tour_id' => $this->league->tour_id, 'team_name' => 'Aardvarks']);
        $this->postJson('/api/results', ['match_id' => MatchModel::factory()->between($this->home, $this->away)->create()->match_id, 'homeTeam_score' => 2, 'awayTeam_score' => 2])->assertCreated();
        $this->postJson('/api/results', ['match_id' => MatchModel::factory()->between($third, $this->home)->create()->match_id, 'homeTeam_score' => 0, 'awayTeam_score' => 1])->assertCreated();

        $this->assertSame(1, $this->standing($this->away)->points);

        $this->getJson("/api/view/tournaments/{$this->league->tour_id}/standings")->assertOk()
            ->assertJsonPath('0.team_id', $this->home->team_id)   // 4 pts
            ->assertJsonPath('0.points', 4)
            ->assertJsonPath('1.team_id', $this->away->team_id)   // 1 pt
            ->assertJsonPath('2.team_id', $third->team_id);       // 0 pts
    }

    public function test_cup_group_results_count_but_knockouts_do_not(): void
    {
        $cup = TournamentModel::factory()->cup()->create(['user_id' => $this->league->user_id]);
        [$a, $b] = TeamModel::factory()->count(2)->create(['tour_id' => $cup->tour_id, 'group_in' => 'A'])->all();
        $c = TeamModel::factory()->create(['tour_id' => $cup->tour_id, 'group_in' => 'B']);

        $group = MatchModel::factory()->between($a, $b, 'Group_Stage')->create();
        $this->postJson('/api/results', ['match_id' => $group->match_id, 'homeTeam_score' => 1, 'awayTeam_score' => 0])->assertCreated();
        $this->assertSame(3, Standings_CupModel::firstWhere('team_id', $a->team_id)->points);

        $final = MatchModel::factory()->between($a, $c, 'Final')->create();
        $this->postJson('/api/results', ['match_id' => $final->match_id, 'homeTeam_score' => 5, 'awayTeam_score' => 0])->assertCreated();
        $this->assertSame(3, Standings_CupModel::firstWhere('team_id', $a->team_id)->points);
        $this->assertSame(0, Standings_CupModel::firstWhere('team_id', $c->team_id)->played);

        $this->getJson("/api/view/tournaments/{$cup->tour_id}/standings")->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.group', 'A')
            ->assertJsonPath('0.teams.0.team_id', $a->team_id)
            ->assertJsonPath('1.group', 'B');
    }

    public function test_penalties_decide_drawn_knockouts_only(): void
    {
        $cup = TournamentModel::factory()->cup()->create(['user_id' => $this->league->user_id]);
        [$a, $b] = TeamModel::factory()->count(2)->create(['tour_id' => $cup->tour_id, 'group_in' => 'A'])->all();
        $group = MatchModel::factory()->between($a, $b, 'Group_Stage')->create();
        $semi = MatchModel::factory()->between($a, $b, 'Semi_Final')->create();

        $pens = fn ($match, $h, $aw, $hp, $ap) => $this->postJson('/api/results', [
            'match_id' => $match->match_id, 'homeTeam_score' => $h, 'awayTeam_score' => $aw,
            'home_score_pen' => $hp, 'away_score_pen' => $ap,
        ]);

        $pens($group, 1, 1, 4, 3)->assertJsonValidationErrors('home_score_pen');
        $pens($semi, 2, 1, 4, 3)->assertJsonValidationErrors('home_score_pen');
        $pens($semi, 1, 1, 3, 3)->assertJsonValidationErrors('home_score_pen');
        $pens($semi, 1, 1, 3, null)->assertJsonValidationErrors('home_score_pen');
        $pens($semi, 1, 1, 3, 5)->assertCreated();

        $this->getJson("/api/view/tournaments/{$cup->tour_id}/results")->assertOk()
            ->assertJsonPath('0.away_score_pen', 5)
            ->assertJsonPath('0.winner', $b->team_id);
    }

    public function test_results_and_matches_of_other_owners_are_off_limits(): void
    {
        $foreignTour = TournamentModel::factory()->create();
        [$x, $y] = TeamModel::factory()->count(2)->create(['tour_id' => $foreignTour->tour_id])->all();
        $match = MatchModel::factory()->between($x, $y)->create();
        $result = ResultModel::create([
            'match_id' => $match->match_id, 'tour_id' => $foreignTour->tour_id,
            'home_team' => $x->team_id, 'away_team' => $y->team_id, 'home_score' => 1, 'away_score' => 0,
        ]);

        $this->postJson('/api/results', ['match_id' => $match->match_id, 'homeTeam_score' => 1, 'awayTeam_score' => 0])->assertNotFound();
        $this->deleteJson("/api/results/{$result->result_id}")->assertNotFound();
        $this->putJson("/api/matches/{$match->match_id}", ['venue' => 'x', 'kick_off' => '2030-01-01'])->assertNotFound();
        $this->deleteJson("/api/matches/{$match->match_id}")->assertNotFound();
    }

    public function test_sub_user_can_schedule_and_record_results(): void
    {
        $this->signIn(SubUserModel::factory()->create(['user_id' => $this->league->user_id]));

        $id = $this->postJson('/api/matches', [
            'tour_id' => $this->league->tour_id, 'homeTeam' => $this->home->team_id, 'awayTeam' => $this->away->team_id,
            'venue' => 'V', 'kick_off' => '2030-01-01T10:00:00Z',
        ])->assertCreated()->json('match_id');

        $this->postJson('/api/results', ['match_id' => $id, 'homeTeam_score' => 0, 'awayTeam_score' => 2])->assertCreated();
        $this->getJson("/api/tournaments/{$this->league->tour_id}/results")->assertOk()->assertJsonPath('0.winner', $this->away->team_id);
    }

    public function test_dashboard_counts_only_own_data(): void
    {
        MatchModel::factory()->between($this->home, $this->away)->create();
        TeamModel::factory()->count(3)->create(); // someone else's

        $this->getJson('/api/dashboard')->assertOk()->assertExactJson([
            'tournaments' => 1, 'teams' => 2, 'matches' => 1, 'results' => 0, 'live' => 0,
        ]);
    }
}
