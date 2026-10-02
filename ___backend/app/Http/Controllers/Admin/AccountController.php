<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubUserModel;
use App\Models\UserModel;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    private const RESET_TOKEN_MINUTES = 60;

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (UserModel::emailIsTaken($data['email'])) {
            throw ValidationException::withMessages(['email' => 'An account with this e-mail already exists.']);
        }

        $account = UserModel::create([
            'email' => Str::lower($data['email']),
            'password' => $data['password'],
            'firstname' => $data['firstname'] ?? null,
            'lastname' => $data['lastname'] ?? null,
            'role' => 'admin',
        ]);

        return response()->json($this->sessionPayload($account), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $account = UserModel::findAccountByEmail(Str::lower($data['email']));

        if (! $account || ! Hash::check($data['password'], $account->password)) {
            return response()->json(['message' => 'Incorrect e-mail or password.'], 401);
        }

        if ($account instanceof SubUserModel && ! $account->isActive()) {
            return response()->json(['message' => 'This account has been disabled.'], 403);
        }

        return response()->json($this->sessionPayload($account));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($this->account($request)->profile());
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $account = $this->account($request);

        $account->update($request->validate([
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
        ]));

        return response()->json($account->profile());
    }

    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)],
        ]);

        $account = $this->account($request);

        if (! Hash::check($data['current_password'], $account->password)) {
            throw ValidationException::withMessages(['current_password' => 'Your current password is incorrect.']);
        }

        $account->update(['password' => $data['password']]);

        // Sign out every other device.
        $account->tokens()->whereKeyNot($account->currentAccessToken()->getKey())->delete();

        return response()->json(['message' => 'Password updated.']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $email = Str::lower($request->validate(['email' => ['required', 'email']])['email']);

        if ($account = UserModel::findAccountByEmail($email)) {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => Hash::make($token), 'created_at' => now()],
            );

            $url = rtrim(config('app.frontend_url'), '/').'/reset-password?'.http_build_query([
                'token' => $token,
                'email' => $email,
            ]);

            $account->notify(new ResetPasswordNotification($url, self::RESET_TOKEN_MINUTES));
        }

        // Same answer whether or not the account exists, so e-mails cannot be probed.
        return response()->json(['message' => 'If that e-mail has an account, a reset link is on its way.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $email = Str::lower($data['email']);
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        $valid = $record
            && Hash::check($data['token'], $record->token)
            && now()->subMinutes(self::RESET_TOKEN_MINUTES)->lt($record->created_at);

        $account = $valid ? UserModel::findAccountByEmail($email) : null;

        if (! $account) {
            throw ValidationException::withMessages(['token' => 'This reset link is invalid or has expired.']);
        }

        $account->update(['password' => $data['password']]);
        $account->tokens()->delete();
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['message' => 'Password reset. You can now log in.']);
    }

    private function sessionPayload(UserModel|SubUserModel $account): array
    {
        return [
            ...$account->profile(),
            'token' => $account->createToken('spa')->plainTextToken,
        ];
    }
}
