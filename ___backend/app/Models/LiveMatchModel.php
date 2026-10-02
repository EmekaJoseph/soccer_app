<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A match currently being scored live. Rows only exist while the match is on.
 */
class LiveMatchModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_live';

    protected $primaryKey = 'live_id';

    protected $guarded = ['live_id'];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'home_team_score' => 'integer',
            'away_team_score' => 'integer',
            'curr_time' => 'integer',
            'isPaused' => 'boolean',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'home_team');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'away_team');
    }

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function creatorAccount(): UserModel|SubUserModel|null
    {
        [$type, $id] = str_contains((string) $this->creator, ':')
            ? explode(':', $this->creator, 2)
            : [null, $this->creator]; // rows written before creator keys were namespaced

        return match ($type) {
            'admin' => UserModel::find($id),
            'sub' => SubUserModel::find($id),
            default => SubUserModel::find($id) ?? UserModel::find($id),
        };
    }
}
