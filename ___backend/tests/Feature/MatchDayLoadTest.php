<?php

namespace Tests\Feature;

use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class MatchDayLoadTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $owner;

    private TournamentModel $tour;

    private MatchModel $match;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-11-05 15:00:00');
        $this->owner = UserModel::factory()->create();
        $this->tour = TournamentModel::factory()->create(['user_id' => $this->owner->user_id]);
        [$home, $away] = TeamModel::factory()->count(2)->create(['tour_id' => $this->tour->tour_id])->all();
        $this->match = MatchModel::factory()->between($home, $away)->create();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function goLive(int $minute = 10, bool $paused = false): int
    {
        $this->signIn($this->owner);
        $liveId = $this->postJson('/api/live', ['match_id' => $this->match->match_id])->assertCreated()->json('live_id');
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 0, 'away_team_score' => 0, 'curr_time' => $minute, 'isPaused' => $paused])->assertOk();

        return $liveId;
    }

    private function publicMinute(): int
    {
        return $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/live")->assertOk()->json('0.curr_time');
    }

    // ---------------------------------------------------------------- rate limits

    public function test_many_fans_behind_one_ip_are_not_cut_off_at_the_old_limit(): void
    {
        foreach (range(1, 130) as $request) {
            $response = $this->getJson("/api/view/tournaments/{$this->tour->tour_id}/live");
        }

        $response->assertOk();
    }

    public function test_public_reads_still_have_a_ceiling_per_ip(): void
    {
        $limit = RateLimiter::limiter('public-read')(Request::create('/', 'GET', server: ['REMOTE_ADDR' => '1.2.3.4']));

        $this->assertSame(1200, $limit->maxAttempts);
        $this->assertSame('1.2.3.4', $limit->key);
    }

    public function test_the_signed_in_api_keeps_its_limit(): void
    {
        $this->signIn($this->owner);

        foreach (range(1, 121) as $request) {
            $response = $this->getJson('/api/me');
        }

        $response->assertTooManyRequests();
    }

    public function test_predictions_and_feedback_allow_a_busy_shared_connection_but_stay_limited(): void
    {
        foreach (range(1, 30) as $request) {
            $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/feedback", ['feedbackText' => 'Great'])->assertCreated();
        }

        $this->postJson("/api/view/tournaments/{$this->tour->tour_id}/feedback", ['feedbackText' => 'Great'])->assertTooManyRequests();
    }

    public function test_login_keeps_its_strict_limit(): void
    {
        foreach (range(1, 10) as $request) {
            $this->postJson('/api/login', ['email' => 'nobody@example.com', 'password' => 'x'])->assertUnauthorized();
        }

        $this->postJson('/api/login', ['email' => 'nobody@example.com', 'password' => 'x'])->assertTooManyRequests();
    }

    // ---------------------------------------------------------------- match clock

    public function test_the_clock_keeps_running_when_the_scorer_goes_quiet(): void
    {
        $this->goLive(10);

        Carbon::setTestNow(now()->addMinutes(20));

        $this->assertSame(30, $this->publicMinute());
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live")->assertJsonPath('0.curr_time', 30);
        $this->getJson("/api/tournaments/{$this->tour->tour_id}/live/all")->assertJsonPath('0.curr_time', 30);
    }

    public function test_a_part_minute_does_not_count_until_it_is_complete(): void
    {
        $this->goLive(10);

        Carbon::setTestNow(now()->addSeconds(59));
        $this->assertSame(10, $this->publicMinute());

        Carbon::setTestNow(now()->addSecond());
        $this->assertSame(11, $this->publicMinute());
    }

    public function test_a_paused_clock_stays_still_and_resumes_from_where_it_stopped(): void
    {
        $liveId = $this->goLive(45, paused: true);

        Carbon::setTestNow(now()->addMinutes(15));
        $this->assertSame(45, $this->publicMinute());

        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 0, 'away_team_score' => 0, 'curr_time' => 45, 'isPaused' => false])->assertOk();

        Carbon::setTestNow(now()->addMinutes(2));
        $this->assertSame(47, $this->publicMinute());
    }

    public function test_the_scorer_can_still_correct_the_minute(): void
    {
        $liveId = $this->goLive(10);
        Carbon::setTestNow(now()->addMinutes(5));

        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 0, 'away_team_score' => 0, 'curr_time' => 40])
            ->assertOk()->assertJsonPath('curr_time', 40);

        Carbon::setTestNow(now()->addMinutes(1));
        $this->assertSame(41, $this->publicMinute());
    }

    public function test_a_goal_mid_minute_does_not_slow_the_clock(): void
    {
        $liveId = $this->goLive(10);

        Carbon::setTestNow(now()->addSeconds(50));
        $this->putJson("/api/live/{$liveId}", ['home_team_score' => 1, 'away_team_score' => 0, 'curr_time' => 10])->assertOk();

        Carbon::setTestNow(now()->addSeconds(10)); // a full minute since the clock was last set
        $this->assertSame(11, $this->publicMinute());
    }

    public function test_the_clock_stops_at_the_maximum(): void
    {
        $this->goLive(199);

        Carbon::setTestNow(now()->addHours(3));

        $this->assertSame(200, $this->publicMinute());
    }

    public function test_a_match_already_live_before_this_change_shows_its_saved_minute(): void
    {
        $liveId = $this->goLive(33);
        LiveMatchModel::whereKey($liveId)->update(['clock_synced_at' => null]);

        Carbon::setTestNow(now()->addMinutes(10));

        $this->assertSame(33, $this->publicMinute());
    }
}
