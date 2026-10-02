<?php

namespace Tests\Feature;

use App\Models\SubUserModel;
use App\Models\UserModel;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_register_and_is_signed_in(): void
    {
        $response = $this->postJson('/api/register', [
            'firstname' => 'Ada',
            'email' => 'Ada@Example.com',
            'password' => 'secret-pass',
            'password_confirmation' => 'secret-pass',
        ]);

        $response->assertCreated()
            ->assertJson(['email' => 'ada@example.com', 'firstname' => 'Ada', 'role' => 'admin'])
            ->assertJsonStructure(['id', 'token']);

        $this->assertTrue(Hash::check('secret-pass', UserModel::firstWhere('email', 'ada@example.com')->password));
    }

    public function test_register_rejects_duplicate_email_across_account_types(): void
    {
        SubUserModel::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/register', [
            'email' => 'taken@example.com',
            'password' => 'secret-pass',
            'password_confirmation' => 'secret-pass',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_register_requires_matching_confirmation_and_min_length(): void
    {
        $this->postJson('/api/register', [
            'email' => 'a@example.com',
            'password' => 'short',
            'password_confirmation' => 'other',
        ])->assertUnprocessable()->assertJsonValidationErrors('password');
    }

    public function test_owner_and_sub_user_can_log_in(): void
    {
        $owner = UserModel::factory()->create(['email' => 'owner@example.com']);
        SubUserModel::factory()->create(['email' => 'sub@example.com', 'user_id' => $owner->user_id]);

        $this->postJson('/api/login', ['email' => 'owner@example.com', 'password' => 'password'])
            ->assertOk()->assertJson(['role' => 'admin', 'id' => $owner->user_id]);

        $this->postJson('/api/login', ['email' => 'sub@example.com', 'password' => 'password'])
            ->assertOk()->assertJson(['role' => 'sub', 'id' => $owner->user_id]);
    }

    public function test_login_rejects_bad_password_and_disabled_sub_users(): void
    {
        UserModel::factory()->create(['email' => 'owner@example.com']);
        SubUserModel::factory()->create(['email' => 'off@example.com', 'is_active' => '0']);

        $this->postJson('/api/login', ['email' => 'owner@example.com', 'password' => 'wrong'])->assertUnauthorized();
        $this->postJson('/api/login', ['email' => 'nobody@example.com', 'password' => 'password'])->assertUnauthorized();
        $this->postJson('/api/login', ['email' => 'off@example.com', 'password' => 'password'])->assertForbidden();
    }

    public function test_protected_routes_need_a_token(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
        $this->getJson('/api/dashboard')->assertUnauthorized();
    }

    public function test_logout_revokes_the_token(): void
    {
        $owner = $this->signIn();

        $this->postJson('/api/logout')->assertOk();

        $this->assertSame(0, $owner->tokens()->count());
    }

    public function test_change_password_checks_current_password_and_signs_out_other_devices(): void
    {
        $owner = UserModel::factory()->create();
        $owner->createToken('other-device');
        $this->signIn($owner);

        $this->putJson('/api/me/password', [
            'current_password' => 'wrong',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertJsonValidationErrors('current_password');

        $this->putJson('/api/me/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk();

        $this->assertTrue(Hash::check('new-password', $owner->fresh()->password));
        $this->assertSame(1, $owner->tokens()->count());
    }

    public function test_profile_can_be_read_and_updated(): void
    {
        $this->signIn(UserModel::factory()->create(['firstname' => 'Old']));

        $this->putJson('/api/me', ['firstname' => 'New', 'lastname' => 'Name'])
            ->assertOk()->assertJson(['firstname' => 'New', 'lastname' => 'Name']);

        $this->getJson('/api/me')->assertOk()->assertJson(['firstname' => 'New', 'role' => 'admin']);
    }

    public function test_password_can_be_reset_through_emailed_link(): void
    {
        Notification::fake();
        $owner = UserModel::factory()->create(['email' => 'owner@example.com']);
        $owner->createToken('old-session');

        $this->postJson('/api/forgot-password', ['email' => 'owner@example.com'])->assertOk();
        // Unknown e-mails get the same answer and no mail.
        $this->postJson('/api/forgot-password', ['email' => 'ghost@example.com'])->assertOk();

        $url = null;
        Notification::assertSentTo($owner, ResetPasswordNotification::class, function ($n) use (&$url) {
            $url = $n->url;

            return true;
        });
        Notification::assertCount(1);

        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        $this->postJson('/api/reset-password', [
            'email' => 'owner@example.com',
            'token' => 'not-the-token',
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
        ])->assertJsonValidationErrors('token');

        $this->postJson('/api/reset-password', [
            'email' => $query['email'],
            'token' => $query['token'],
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
        ])->assertOk();

        $this->assertTrue(Hash::check('brand-new-pass', $owner->fresh()->password));
        $this->assertSame(0, $owner->tokens()->count());

        // Tokens are single use.
        $this->postJson('/api/reset-password', [
            'email' => $query['email'],
            'token' => $query['token'],
            'password' => 'another-pass',
            'password_confirmation' => 'another-pass',
        ])->assertJsonValidationErrors('token');
    }

    public function test_owner_manages_only_their_own_sub_users(): void
    {
        $owner = $this->signIn();
        $strangersSub = SubUserModel::factory()->create();

        $this->postJson('/api/sub-users', ['email' => 'helper@example.com', 'password' => 'helper-pass'])
            ->assertCreated()->assertJsonMissingPath('password');

        $this->getJson('/api/sub-users')->assertOk()->assertJsonCount(1)->assertJsonPath('0.email', 'helper@example.com');

        $this->deleteJson("/api/sub-users/{$strangersSub->subuser_id}")->assertNotFound();
        $this->assertModelExists($strangersSub);

        $mine = SubUserModel::firstWhere('email', 'helper@example.com');
        $this->deleteJson("/api/sub-users/{$mine->subuser_id}")->assertOk();
        $this->assertModelMissing($mine);
        $this->assertSame($owner->user_id, (int) $mine->user_id);
    }

    public function test_sub_users_cannot_reach_owner_only_routes(): void
    {
        $this->signIn(SubUserModel::factory()->create());

        $this->getJson('/api/sub-users')->assertForbidden();
        $this->postJson('/api/tournaments', ['tour_title' => 'X', 'tour_type' => 'cup'])->assertForbidden();
        $this->getJson('/api/feedback')->assertForbidden();
    }
}
