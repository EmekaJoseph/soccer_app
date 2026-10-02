<?php

namespace App\Models\Concerns;

use App\Models\SubUserModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for the two account types (owners and sub-users).
 *
 * Every tournament belongs to an owner; a sub-user acts on behalf of its
 * owner, so `ownerId()` is the id used to scope all tournament data.
 */
trait IsAccount
{
    abstract public function isAdmin(): bool;

    abstract public function ownerId(): int;

    /** Identifier stored in tbl_live.creator, unique across both account tables. */
    abstract public function creatorKey(): string;

    /** Tournaments this account may manage. */
    public function tournaments(): Builder
    {
        return TournamentModel::query()->where('user_id', $this->ownerId());
    }

    public function owns(?TournamentModel $tournament): bool
    {
        return $tournament !== null && (int) $tournament->user_id === $this->ownerId();
    }

    public function profile(): array
    {
        return [
            'id' => $this->ownerId(),
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'role' => $this->isAdmin() ? 'admin' : 'sub',
        ];
    }

    /** Find an owner or sub-user account by e-mail (e-mails are unique across both tables). */
    public static function findAccountByEmail(string $email): UserModel|SubUserModel|null
    {
        return UserModel::where('email', $email)->first()
            ?? SubUserModel::where('email', $email)->first();
    }

    public static function emailIsTaken(string $email): bool
    {
        return UserModel::where('email', $email)->exists()
            || SubUserModel::where('email', $email)->exists();
    }
}
