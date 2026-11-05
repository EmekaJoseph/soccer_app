<?php

namespace Tests\Feature;

use App\Models\MatchModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndLiveMatchTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $owner;

    private SubUserModel $scorer;

    private TournamentModel $tour;

    private MatchModel $match;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = UserModel::factory()->create();
        $this->scorer = SubUserModel::factory()->create(['user_id' => $this->owner->user_id]);
        $this->tour = TournamentModel::factory()->create(['user_id' => $this->owner->user_id, 'tour_type' => 'cup']);
        [$home, $away] = TeamModel::factory()->count(2)->create(['tour_id' => $this->tour->tour_id])->all();
        $this->match = MatchModel::factory()->between($home, $away)->create(['match_stage' => 'Final']);
    }

    /** Laravel remembers the signed-in user between requests in a test, so clear it when switching. */
    protected function signIn(UserModel|SubUserModel|null $account = null): UserModel|SubUserModel
    {
        $this->app['auth']->forgetGuards();

        return parent::signIn($account);
    }

    private function liveMatch(int $home = 1, int $away = 1): int
    {
        $liveId = $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertCreated()->json('live_id');
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => $home, 'away_team_score' => $away, 'curr_time' => 90])->assertOk();

        return $liveId;
    }

    public function test_owner_can_end_a_match_someone_else_is_scoring_and_save_the_result(): void
    {
        $this->signIn($this->scorer);
        $liveId = $this->liveMatch(2, 1);

        $this->signIn($this->owner);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertOk()->assertJsonPath('0.live_id', $liveId);
        $this->postJson("/api/live/{$liveId}/end", ['save' => true])->assertOk();

        $this->assertDatabaseCount('tbl_live', 0);
        $this->assertDatabaseHas('tbl_results', ['match_id' => $this->match->match_id, 'home_score' => 2, 'away_score' => 1]);
    }

    public function test_owner_can_clear_a_match_left_behind_by_a_removed_scorer(): void
    {
        $this->signIn($this->scorer);
        $liveId = $this->liveMatch();
        $this->scorer->delete();

        $this->signIn($this->owner);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertOk()->assertJsonPath('0.creator', null);
        $this->postJson("/api/live/{$liveId}/end")->assertOk();

        $this->assertDatabaseCount('tbl_live', 0);
        $this->assertDatabaseCount('tbl_results', 0);
    }

    public function test_a_scorer_still_cannot_end_someone_elses_match(): void
    {
        $this->signIn($this->owner);
        $liveId = $this->liveMatch();

        $this->signIn($this->scorer);
        $this->postJson("/api/live/{$liveId}/end", ['save' => true])->assertForbidden();

        $this->assertDatabaseCount('tbl_live', 1);
    }

    public function test_ending_a_drawn_knockout_can_record_the_penalties(): void
    {
        $this->signIn($this->owner);
        $liveId = $this->liveMatch();

        $this->postJson("/api/live/{$liveId}/end", ['save' => true, 'home_score_pen' => 4, 'away_score_pen' => 3])->assertOk();

        $this->assertDatabaseHas('tbl_results', ['match_id' => $this->match->match_id, 'home_score_pen' => 4, 'away_score_pen' => 3]);
        $winner = $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/results")->json('0.winner');
        $this->assertSame($this->match->home_team, $winner);
    }

    public function test_a_drawn_knockout_can_still_be_saved_without_penalties(): void
    {
        $this->signIn($this->owner);
        $liveId = $this->liveMatch();

        $this->postJson("/api/live/{$liveId}/end", ['save' => true])->assertOk();

        $this->assertDatabaseHas('tbl_results', ['match_id' => $this->match->match_id, 'home_score_pen' => null]);
    }

    public function test_invalid_penalties_keep_the_match_live_and_save_nothing(): void
    {
        $this->signIn($this->owner);
        $liveId = $this->liveMatch();

        $this->postJson("/api/live/{$liveId}/end", ['save' => true, 'home_score_pen' => 3, 'away_score_pen' => 3])->assertUnprocessable();
        $this->postJson("/api/live/{$liveId}/end", ['save' => true, 'home_score_pen' => 3])->assertUnprocessable();
        $this->postJson("/api/live/{$liveId}/end", ['save' => true, 'home_score_pen' => -1, 'away_score_pen' => 3])->assertUnprocessable();

        $this->assertDatabaseCount('tbl_live', 1);
        $this->assertDatabaseCount('tbl_results', 0);
    }

    public function test_penalties_are_rejected_when_the_match_was_not_a_draw_or_not_a_knockout(): void
    {
        $this->signIn($this->owner);
        $liveId = $this->liveMatch(2, 1);
        $this->postJson("/api/live/{$liveId}/end", ['save' => true, 'home_score_pen' => 4, 'away_score_pen' => 3])->assertUnprocessable();
        $this->assertDatabaseCount('tbl_live', 1);

        $this->postJson("/api/live/{$liveId}/end")->assertOk();
        $this->match->update(['match_stage' => 'Group_Stage']);

        $groupGame = $this->liveMatch();
        $this->postJson("/api/live/{$groupGame}/end", ['save' => true, 'home_score_pen' => 4, 'away_score_pen' => 3])->assertUnprocessable();
        $this->assertDatabaseCount('tbl_results', 0);
    }
}
