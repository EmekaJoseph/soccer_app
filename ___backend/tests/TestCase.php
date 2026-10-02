<?php

namespace Tests;

use App\Models\SubUserModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** Authenticate following requests with a real Sanctum token for the account. */
    protected function signIn(UserModel|SubUserModel|null $account = null): UserModel|SubUserModel
    {
        $account ??= UserModel::factory()->create();

        $this->withToken($account->createToken('test')->plainTextToken);

        return $account;
    }
}
