<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubUserModel;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class SubUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            SubUserModel::where('user_id', $this->account($request)->ownerId())->orderBy('email')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', Password::min(8)],
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
        ]);

        if (UserModel::emailIsTaken($data['email'])) {
            throw ValidationException::withMessages(['email' => 'An account with this e-mail already exists.']);
        }

        $subUser = SubUserModel::create([
            ...$data,
            'email' => Str::lower($data['email']),
            'user_id' => $this->account($request)->ownerId(),
            'role' => 'sub',
            'is_active' => '1',
            'created_at' => now(),
        ]);

        return response()->json($subUser, 201);
    }

    public function destroy(Request $request, SubUserModel $subUser): JsonResponse
    {
        abort_unless($subUser->ownerId() === $this->account($request)->ownerId(), 404);

        $subUser->tokens()->delete();
        $subUser->delete();

        return response()->json(['message' => 'User removed.']);
    }
}
