<?php

namespace App\Models;

use App\Models\Concerns\IsAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * A tournament owner (role "admin").
 */
class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, IsAccount, Notifiable;

    protected $table = 'tbl_users';

    protected $primaryKey = 'user_id';

    protected $guarded = ['user_id'];

    protected $hidden = ['password'];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'no_of_leagues' => 'integer',
            'no_of_cups' => 'integer',
        ];
    }

    public function relatedTournaments(): HasMany
    {
        return $this->hasMany(TournamentModel::class, 'user_id');
    }

    public function relatedSubUsers(): HasMany
    {
        return $this->hasMany(SubUserModel::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return true;
    }

    public function ownerId(): int
    {
        return (int) $this->user_id;
    }

    public function creatorKey(): string
    {
        return 'admin:'.$this->user_id;
    }
}
