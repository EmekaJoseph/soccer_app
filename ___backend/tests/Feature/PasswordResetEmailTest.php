<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Tests\TestCase;

class PasswordResetEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_mail_failure_looks_the_same_as_an_unknown_email_and_is_logged(): void
    {
        UserModel::factory()->create(['email' => 'owner@example.com']);
        Notification::shouldReceive('send')->once()->andThrow(new RuntimeException('SMTP down'));
        Log::spy();

        $known = $this->postJson('/api/forgot-password', ['email' => 'owner@example.com'])->assertOk();
        $unknown = $this->postJson('/api/forgot-password', ['email' => 'ghost@example.com'])->assertOk();

        $this->assertSame($unknown->json(), $known->json());
        Log::shouldHaveReceived('error')->once();
    }
}
