<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamModel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tbl_teams';

    protected $primaryKey = 'team_id';

    protected $guarded = ['team_id'];

    protected function casts(): array
    {
        return [
            'match_played' => 'integer',
        ];
    }

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(PlayerModel::class, 'team_id');
    }
}
