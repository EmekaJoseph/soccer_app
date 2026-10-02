<?php

namespace App\Models;

use App\Models\Concerns\IsAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * A helper account created by an owner to run matches, results and live
 * scores for the owner's tournaments (role "sub").
 */
class SubUserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, IsAccount, Notifiable;

    protected $table = 'tbl_subusers';

    protected $primaryKey = 'subuser_id';

    protected $guarded = ['subuser_id'];

    protected $hidden = ['password'];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return false;
    }

    public function ownerId(): int
    {
        return (int) $this->user_id;
    }

    public function creatorKey(): string
    {
        return 'sub:'.$this->subuser_id;
    }

    public function isActive(): bool
    {
        return (string) $this->is_active !== '0';
    }
}
