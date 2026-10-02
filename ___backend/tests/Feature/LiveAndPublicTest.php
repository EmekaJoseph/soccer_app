<?php

namespace Tests\Feature;

use App\Events\LiveMatchEnded;
use App\Events\LiveMatchStarted;
use App\Events\LiveMatchUpdated;
use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\Standings_LeagueModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use App\Services\MatchResultsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LiveAndPublicTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $owner;

    private TournamentModel $tour;

    private MatchModel $match;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = UserModel::factory()->create();
        $this->tour = TournamentModel::factory()->create(['user_id' => $this->owner->user_id]);
        [$home, $away] = TeamModel::factory()->count(2)->create(['tour_id' => $this->tour->tour_id])->all();
        $this->match = MatchModel::factory()->between($home, $away)->create();
    }

    public function test_full_live_match_flow_broadcasts_on_the_tournament_channel(): void
    {
        Event::fake([LiveMatchStarted::class, LiveMatchUpdated::class, LiveMatchEnded::class]);
        $this->signIn($this->owner);

        $liveId = $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertCreated()
            ->assertJson(['home_team_score' => 0, 'curr_time' => 0])
            ->json('live_id');

        Event::assertDispatched(LiveMatchStarted::class, fn ($e) => $e->tour_id === $this->tour->tour_id
            && $e->broadcastOn()[0]->name === 'tournament.'.$this->tour->tour_id
            && $e->broadcastAs() === 'live.started');

        $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertConflict();

        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 2, 'away_team_score' => 1, 'curr_time' => 37, 'isPaused' => true])
            ->assertOk()->assertJson(['home_team_score' => 2, 'curr_time' => 37, 'isPaused' => true]);

        Event::assertDispatched(LiveMatchUpdated::class, fn ($e) => $e->results['home_team_score'] === 2 && $e->live_id === $liveId);

        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live")->assertOk()->assertJsonCount(1);
        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/live")->assertOk()
            ->assertJsonPath('0.home_team_score', 2)
            ->assertJsonPath('0.home_team.team_id', $this->match->home_team);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertOk()->assertJsonPath('0.isMe', 'You');

        $this->postJson("/api/live/{$liveId}/end", ['save' => true])->assertOk()
            ->assertJsonPath('result.home_score', 2);

        Event::assertDispatched(LiveMatchEnded::class);
        $this->assertDatabaseCount('tbl_live', 0);
        $this->assertSame(3, Standings_LeagueModel::firstWhere('team_id', $this->match->home_team)->points);
    }

    public function test_ending_without_saving_records_nothing(): void
    {
        Event::fake();
        $this->signIn($this->owner);
        $liveId = $this->postJson('/api/live', ['match_id' => $this->match->match_id])->json('live_id');

        $this->postJson("/api/live/{$liveId}/end")->assertOk()->assertJsonPath('result', null);
        $this->assertDatabaseCount('tbl_results', 0);
    }

    public function test_live_match_cannot_start_once_result_exists(): void
    {
        Event::fake();
        app(MatchResultsService::class)->saveResult($this->match, 1, 1);
        $this->signIn($this->owner);

        $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertConflict();
    }

    public function test_sub_users_only_control_their_own_live_matches(): void
    {
        Event::fake();
        $subA = SubUserModel::factory()->create(['user_id' => $this->owner->user_id]);
        $subB = SubUserModel::factory()->create(['user_id' => $this->owner->user_id]);

        $this->signIn($subA);
        $liveId = $this->postJson('/api/live', ['match_id' => $this->match->match_id])->json('live_id');
        $this->assertSame('sub:'.$subA->subuser_id, LiveMatchModel::find($liveId)->creator);

        $this->app['auth']->forgetGuards();
        $this->signIn($subB);
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 1, 'away_team_score' => 0, 'curr_time' => 1])->assertForbidden();
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live")->assertOk()->assertJsonCount(0);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertForbidden();

        // ...but the owner can step in.
        $this->app['auth']->forgetGuards();
        $this->signIn($this->owner);
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 1, 'away_team_score' => 0, 'curr_time' => 1])->assertOk();
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertOk()
            ->assertJsonPath('0.creator.email', $subA->email)
            ->assertJsonPath('0.isMe', null);
    }

    public function test_legacy_numeric_creator_is_still_resolved(): void
    {
        $sub = SubUserModel::factory()->create(['user_id' => $this->owner->user_id]);
        $live = LiveMatchModel::create([
            'match_id' => $this->match->match_id, 'tour_id' => $this->tour->tour_id,
            'home_team' => $this->match->home_team, 'away_team' => $this->match->away_team,
            'creator' => (string) $sub->subuser_id,
        ]);

        $this->assertTrue($live->creatorAccount()->is($sub));
    }

    public function test_public_tournament_data_and_team_ratings(): void
    {
        $service = app(MatchResultsService::class);
        $service->saveResult($this->match, 2, 0);

        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}")->assertOk()
            ->assertJson(['tour_title' => $this->tour->tour_title])
            ->assertJsonMissingPath('user_id');

        $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/teams")->assertOk()
            ->assertJsonPath('0.team_id', $this->match->home_team)
            ->assertJsonPath('0.rating', 100)
            ->assertJsonPath('0.scored', 2)
            ->assertJsonPath('1.rating', 0)
            ->assertJsonPath('1.lost', 1);

        $this->getJson('/api/view/tournaments/does-not-exist/standings')->assertNotFound();
    }

    public function test_fans_predict_once_per_phone_and_owner_finds_winners(): void
    {
        [$first, $second] = [$this->match->home_team, $this->match->away_team];
        $third = TeamModel::factory()->create(['tour_id' => $this->tour->tour_id])->team_id;
        $url = "/api/view/tournaments/{$this->tour->tour_id}/predictions";
        $guess = ['first_place' => $first, 'second_place' => $second, 'third_place' => $third, 'full_name' => 'Fan One', 'phone_number' => '0803 123 4567'];

        $this->postJson($url, [...$guess, 'second_place' => $first])->assertJsonValidationErrors('second_place');
        $this->postJson($url, [...$guess, 'third_place' => TeamModel::factory()->create()->team_id])->assertJsonValidationErrors('first_place');
        $this->postJson($url, [...$guess, 'phone_number' => 'abc'])->assertJsonValidationErrors('phone_number');

        $this->postJson($url, $guess)->assertCreated();
        $this->postJson($url, [...$guess, 'phone_number' => '08031234567'])->assertConflict();
        $this->postJson($url, [...$guess, 'full_name' => 'Fan Two', 'phone_number' => '0809', 'second_place' => $third, 'third_place' => $second])
            ->assertJsonValidationErrors('phone_number');
        $this->postJson($url, [...$guess, 'full_name' => 'Fan Two', 'phone_number' => '08090000000', 'second_place' => $third, 'third_place' => $second])
            ->assertCreated();

        // Predictions hold personal data: owner only.
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/predictions")->assertUnauthorized();

        $this->signIn($this->owner);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/predictions")->assertOk()
            ->assertJsonCount(2)
            ->assertJsonMissingPath('0.device_ip');

        $this->getJson("/api/tournaments/{$this->tour->tour_id}/predictions/winners?first={$first}&second={$second}&third=0")
            ->assertOk()->assertJsonCount(1)->assertJsonPath('0.full_name', 'Fan One');
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/predictions/winners?first={$first}")
            ->assertOk()->assertJsonCount(2);
    }

    public function test_fan_feedback_is_visible_only_to_the_owner(): void
    {
        $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/feedback", ['feedbackText' => ''])
            ->assertJsonValidationErrors('feedbackText');
        $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/feedback", ['name' => 'Fan', 'feedbackText' => 'Great app'])
            ->assertCreated();

        $this->signIn();
        $this->getJson('/api/feedback')->assertOk()->assertJsonCount(0);

        $this->app['auth']->forgetGuards();
        $this->signIn($this->owner);
        $this->getJson('/api/feedback')->assertOk()->assertJsonCount(1)
            ->assertJsonPath('0.feedbackText', 'Great app')
            ->assertJsonPath('0.tour_title', $this->tour->tour_title);
    }

    public function test_demo_seeder_runs(): void
    {
        $this->seed();

        $this->assertDatabaseHas('tbl_users', ['email' => 'demo@soccer.test']);
        $this->assertDatabaseCount('tbl_tournament', 3);
    }
}
