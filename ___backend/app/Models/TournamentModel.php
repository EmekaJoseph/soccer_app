<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentModel extends Model
{
    use HasFactory, HasUlids;

    public const TYPE_CUP = 'cup';

    public const TYPE_LEAGUE = 'league';

    protected $table = 'tbl_tournament';

    protected $primaryKey = 'tour_id';

    protected $guarded = ['tour_id'];

    protected $hidden = ['user_id'];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
        ];
    }

    public function isCup(): bool
    {
        return $this->tour_type === self::TYPE_CUP;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function relatedTeams(): HasMany
    {
        return $this->hasMany(TeamModel::class, 'tour_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchModel::class, 'tour_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(ResultModel::class, 'tour_id');
    }

    public function liveMatches(): HasMany
    {
        return $this->hasMany(LiveMatchModel::class, 'tour_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(PlayerModel::class, 'tour_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(PredictionModel::class, 'tour_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(FeedbackModel::class, 'tour_id');
    }

    public function cupStandings(): HasMany
    {
        return $this->hasMany(Standings_CupModel::class, 'tour_id');
    }

    public function leagueStandings(): HasMany
    {
        return $this->hasMany(Standings_LeagueModel::class, 'tour_id');
    }

    /** The standings relation that applies to this tournament's format. */
    public function standings(): HasMany
    {
        return $this->isCup() ? $this->cupStandings() : $this->leagueStandings();
    }
}
