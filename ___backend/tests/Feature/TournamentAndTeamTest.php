<?php

namespace Tests\Feature;

use App\Models\MatchModel;
use App\Models\PlayerModel;
use App\Models\Standings_CupModel;
use App\Models\Standings_LeagueModel;
use App\Models\SubUserModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Services\MatchResultsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TournamentAndTeamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('uploads');
    }

    public function test_owner_creates_lists_updates_and_deletes_a_tournament_with_logo(): void
    {
        $owner = $this->signIn();

        $id = $this->post('/api/tournaments', [
            'tour_title' => 'Summer Cup',
            'tour_type' => 'cup',
            'tour_desc' => 'Five-a-side',
            'tour_logo' => UploadedFile::fake()->image('logo.png', 400, 400),
        ], ['Accept' => 'application/json'])->assertCreated()->json('tour_id');

        $logo = TournamentModel::find($id)->tour_logo;
        $this->assertStringStartsWith('tour_logos/', $logo);
        Storage::disk('uploads')->assertExists($logo);
        $this->assertSame(1, $owner->fresh()->no_of_cups);

        $this->getJson('/api/tournaments')->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $id)
            ->assertJsonPath('0.title', 'Summer Cup')
            ->assertJsonPath('0.type', 'cup')
            ->assertJsonMissingPath('0.user_id');

        $this->postJson('/api/tournaments', ['tour_title' => 'Summer Cup', 'tour_type' => 'league'])
            ->assertJsonValidationErrors('tour_title');

        $this->post("/api/tournaments/{$id}", [
            '_method' => 'PUT',
            'tour_title' => 'Winter Cup',
            'tour_logo' => UploadedFile::fake()->image('new.jpg'),
        ], ['Accept' => 'application/json'])->assertOk()->assertJsonPath('tour_title', 'Winter Cup');

        Storage::disk('uploads')->assertMissing($logo);

        $this->deleteJson("/api/tournaments/{$id}")->assertOk();
        $this->assertDatabaseMissing('tbl_tournament', ['tour_id' => $id]);
        $this->assertSame(0, $owner->fresh()->no_of_cups);
    }

    public function test_tournament_with_teams_cannot_be_deleted_or_change_format(): void
    {
        $owner = $this->signIn();
        $tour = TournamentModel::factory()->create(['user_id' => $owner->user_id]);
        TeamModel::factory()->create(['tour_id' => $tour->tour_id]);

        $this->deleteJson("/api/tournaments/{$tour->tour_id}")->assertConflict();
        $this->putJson("/api/tournaments/{$tour->tour_id}", ['tour_title' => $tour->tour_title, 'tour_type' => 'cup'])
            ->assertJsonValidationErrors('tour_type');
    }

    public function test_accounts_cannot_see_or_touch_other_owners_tournaments(): void
    {
        $theirs = TournamentModel::factory()->create();
        $team = TeamModel::factory()->create(['tour_id' => $theirs->tour_id]);
        $this->signIn();

        $this->getJson('/api/tournaments')->assertOk()->assertJsonCount(0);
        $this->getJson("/api/tournaments/{$theirs->tour_id}/teams")->assertNotFound();
        $this->getJson("/api/tournaments/{$theirs->tour_id}/matches")->assertNotFound();
        $this->putJson("/api/tournaments/{$theirs->tour_id}", ['tour_title' => 'Mine now'])->assertNotFound();
        $this->deleteJson("/api/tournaments/{$theirs->tour_id}")->assertNotFound();
        $this->postJson('/api/teams', ['tour_id' => $theirs->tour_id, 'team_name' => 'Intruders'])->assertNotFound();
        $this->putJson("/api/teams/{$team->team_id}", ['tour_id' => $theirs->tour_id, 'team_name' => 'Renamed'])->assertNotFound();
        $this->deleteJson("/api/teams/{$team->team_id}")->assertNotFound();
        $this->getJson("/api/tournaments/{$theirs->tour_id}/predictions")->assertNotFound();

        $this->assertSame($team->team_name, $team->fresh()->team_name);
    }

    public function test_sub_user_works_on_the_owners_tournaments(): void
    {
        $tour = TournamentModel::factory()->create();
        $sub = SubUserModel::factory()->create(['user_id' => $tour->user_id]);
        $this->signIn($sub);

        $this->getJson('/api/tournaments')->assertOk()->assertJsonCount(1)->assertJsonPath('0.id', $tour->tour_id);
        $this->getJson('/api/dashboard')->assertOk()->assertJson(['tournaments' => 1]);
        $this->postJson('/api/teams', ['tour_id' => $tour->tour_id, 'team_name' => 'X'])->assertForbidden();
    }

    public function test_cup_team_needs_a_group_and_gets_a_standings_row(): void
    {
        $owner = $this->signIn();
        $cup = TournamentModel::factory()->cup()->create(['user_id' => $owner->user_id]);

        $this->postJson('/api/teams', ['tour_id' => $cup->tour_id, 'team_name' => 'Lions'])
            ->assertJsonValidationErrors('group_in');

        $teamId = $this->post('/api/teams', [
            'tour_id' => $cup->tour_id,
            'team_name' => 'Lions',
            'group_in' => 'B',
            'team_color' => '#ff0000',
            'manager' => 'Coach',
            'team_badge' => UploadedFile::fake()->image('badge.png'),
        ], ['Accept' => 'application/json'])->assertCreated()->json('team_id');

        $this->assertDatabaseHas('tbl_standings_cup', ['team_id' => $teamId, 'tour_id' => $cup->tour_id, 'group_in' => 'B']);
        Storage::disk('uploads')->assertExists(TeamModel::find($teamId)->team_badge);

        $this->postJson('/api/teams', ['tour_id' => $cup->tour_id, 'team_name' => 'Lions', 'group_in' => 'A'])
            ->assertJsonValidationErrors('team_name');

        // Moving group also moves the standings row.
        $this->putJson("/api/teams/{$teamId}", ['tour_id' => $cup->tour_id, 'team_name' => 'Big Lions', 'group_in' => 'C', 'manager' => 'New Coach'])
            ->assertOk()->assertJson(['team_name' => 'Big Lions', 'manager' => 'New Coach', 'group_in' => 'C']);
        $this->assertSame('C', Standings_CupModel::firstWhere('team_id', $teamId)->group_in);

        $this->getJson("/api/tournaments/{$cup->tour_id}/teams")->assertOk()->assertJsonPath('0.players_count', 0);
    }

    public function test_league_team_lands_in_league_standings(): void
    {
        $owner = $this->signIn();
        $league = TournamentModel::factory()->create(['user_id' => $owner->user_id]);

        $teamId = $this->postJson('/api/teams', ['tour_id' => $league->tour_id, 'team_name' => 'Rovers'])
            ->assertCreated()->json('team_id');

        $this->assertTrue(Standings_LeagueModel::where('team_id', $teamId)->exists());
        $this->assertNull(TeamModel::find($teamId)->group_in);
    }

    public function test_deleting_a_team_cleans_up_but_is_blocked_by_results(): void
    {
        $owner = $this->signIn();
        $tour = TournamentModel::factory()->create(['user_id' => $owner->user_id]);
        [$a, $b, $c] = TeamModel::factory()->count(3)->create(['tour_id' => $tour->tour_id])->all();
        $played = MatchModel::factory()->between($a, $b)->create();
        MatchModel::factory()->between($a, $c)->create();
        PlayerModel::create(['first_name' => 'P', 'last_name' => 'Q', 'team_id' => $c->team_id, 'tour_id' => $tour->tour_id]);
        app(MatchResultsService::class)->saveResult($played, 1, 0);

        $this->deleteJson("/api/teams/{$a->team_id}")->assertJsonValidationErrors('team');

        $this->deleteJson("/api/teams/{$c->team_id}")->assertOk();
        $this->assertModelMissing($c);
        $this->assertDatabaseMissing('tbl_players', ['team_id' => $c->team_id]);
        $this->assertDatabaseMissing('tbl_standings_league', ['team_id' => $c->team_id]);
        $this->assertDatabaseMissing('tbl_matches', ['away_team' => $c->team_id]);
    }

    public function test_players_can_be_managed_and_listed_publicly(): void
    {
        $owner = $this->signIn();
        $tour = TournamentModel::factory()->create(['user_id' => $owner->user_id]);
        [$a, $b] = TeamModel::factory()->count(2)->create(['tour_id' => $tour->tour_id])->all();
        $foreignTeam = TeamModel::factory()->create();

        $this->postJson('/api/players', ['first_name' => 'X', 'last_name' => 'Y', 'team_id' => $foreignTeam->team_id])
            ->assertJsonValidationErrors('team_id');

        $playerId = $this->post('/api/players', [
            'first_name' => 'Jay',
            'last_name' => 'Okocha',
            'team_id' => $a->team_id,
            'dob' => '1973-08-14',
            'info' => 'Midfielder',
            'image' => UploadedFile::fake()->image('jay.jpg'),
        ], ['Accept' => 'application/json'])->assertCreated()->json('player_id');

        $player = PlayerModel::find($playerId);
        $this->assertSame($tour->tour_id, $player->tour_id);
        Storage::disk('uploads')->assertExists($player->image);

        $this->putJson("/api/players/{$playerId}", ['first_name' => 'Austin', 'last_name' => 'Okocha', 'team_id' => $b->team_id])
            ->assertOk()->assertJsonPath('team.team_id', $b->team_id);

        $this->getJson("/api/view/tournaments/{$tour->tour_id}/players?search=aust")->assertOk()->assertJsonCount(1);
        $this->getJson("/api/view/tournaments/{$tour->tour_id}/players?team_id={$a->team_id}")->assertOk()->assertJsonCount(0);
        $this->getJson("/api/tournaments/{$tour->tour_id}/teams")->assertJsonFragment(['team_id' => $b->team_id, 'players_count' => 1]);

        $this->deleteJson("/api/players/{$playerId}")->assertOk();
        Storage::disk('uploads')->assertMissing($player->image);
    }
}
