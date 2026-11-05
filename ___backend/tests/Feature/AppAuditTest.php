<?php

namespace Tests\Feature;

use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\PlayerModel;
use App\Models\ResultModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AppAuditTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $owner;

    private TournamentModel $tour;

    private TeamModel $home;

    private TeamModel $away;

    private MatchModel $match;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = UserModel::factory()->create();
        $this->tour = TournamentModel::factory()->create(['user_id' => $this->owner->user_id]);
        [$this->home, $this->away] = TeamModel::factory()->count(2)->create(['tour_id' => $this->tour->tour_id])->all();
        $this->match = MatchModel::factory()->between($this->home, $this->away)->create();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function goLive(): int
    {
        $this->signIn($this->owner);

        return $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertCreated()->json('live_id');
    }

    public function test_public_players_do_not_expose_dates_of_birth_but_the_owner_still_sees_them(): void
    {
        PlayerModel::create([
            'tour_id' => $this->tour->tour_id, 'team_id' => $this->home->team_id,
            'first_name' => 'Sam', 'last_name' => 'Ade', 'dob' => '2012-02-03', 'info' => 'Keeper',
        ]);

        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/players")
            ->assertOk()->assertJsonPath('0.first_name', 'Sam')->assertJsonMissingPath('0.dob');

        $this->signIn($this->owner);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/players")
            ->assertOk()->assertJsonPath('0.dob', '2012-02-03');
    }

    public function test_a_result_cannot_be_recorded_while_the_match_is_being_scored_live(): void
    {
        $this->goLive();

        $this->postJson('/api/results', ['match_id' => $this->match->match_id, 'homeTeam_score' => 1, 'awayTeam_score' => 0])
            ->assertConflict();

        $this->assertDatabaseCount('tbl_results', 0);
    }

    public function test_ending_a_live_match_can_still_save_its_result(): void
    {
        $liveId = $this->goLive();
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 2, 'away_team_score' => 1, 'curr_time' => 90])->assertOk();

        $this->postJson("/api/live/{$liveId}/end", ['save' => true])->assertOk();

        $this->assertDatabaseHas('tbl_results', ['match_id' => $this->match->match_id, 'home_score' => 2]);
        $this->assertDatabaseCount('tbl_live', 0);
    }

    public function test_public_fixtures_leave_out_matches_that_are_live(): void
    {
        $this->goLive();

        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/matches")->assertOk()->assertJsonCount(0);
        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/live")->assertOk()->assertJsonCount(1);
    }

    public function test_changing_kick_off_after_a_result_keeps_the_result_date_in_step(): void
    {
        $this->signIn($this->owner);
        $this->postJson('/api/results', ['match_id' => $this->match->match_id, 'homeTeam_score' => 1, 'awayTeam_score' => 0])->assertCreated();

        $this->putJson("/api/matches/{$this->match->match_id}", ['kick_off' => '2026-12-01T15:00:00Z', 'venue' => 'New ground'])->assertOk();

        $this->assertSame($this->match->fresh()->kick_off, ResultModel::first()->date_played);
    }

    public function test_changing_the_stage_of_a_live_match_updates_what_fans_see(): void
    {
        $this->tour->update(['tour_type' => 'cup']);
        $this->match->update(['match_stage' => 'Group_Stage']);
        $this->goLive();

        $this->putJson("/api/matches/{$this->match->match_id}", [
            'kick_off' => '2026-12-01T15:00:00Z', 'venue' => 'Ground', 'match_stage' => 'Final',
        ])->assertOk();

        $this->assertSame('Final', LiveMatchModel::first()->match_stage);
    }

    public function test_feedback_and_prediction_times_say_they_are_utc(): void
    {
        Carbon::setTestNow('2026-11-05 10:00:00');

        $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/feedback", ['feedbackText' => 'Great'])->assertCreated();

        $third = TeamModel::factory()->create(['tour_id' => $this->tour->tour_id]);
        $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/predictions", [
            'first_place' => $this->home->team_id, 'second_place' => $this->away->team_id, 'third_place' => $third->team_id,
            'full_name' => 'Tobi', 'phone_number' => '08010000000',
        ])->assertCreated();

        $this->signIn($this->owner);
        $this->getJson('/api/feedback')->assertJsonPath('0.created_at', '2026-11-05T10:00:00Z');
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/predictions")->assertJsonPath('0.created_at', '2026-11-05T10:00:00Z');
    }

    public function test_an_email_that_differs_only_by_case_counts_as_taken(): void
    {
        UserModel::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/register', [
            'email' => 'TAKEN@example.com', 'password' => 'password1', 'password_confirmation' => 'password1',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');

        $this->signIn($this->owner);
        $this->postJson('/api/sub-users', ['email' => 'Taken@Example.com', 'password' => 'password1'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');

        $this->assertSame(0, SubUserModel::count());
    }
}
