<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MatchModel extends Model
{
    use HasFactory, HasUlids;

    public const STAGES = [
        'Friendly', 'Group_Stage', 'Round_of_32', 'Round_of_16', 'Knock_Out',
        'Quarter_Final', 'Semi_Final', 'Third_place', 'Final',
    ];

    public const GROUP_STAGE = 'Group_Stage';

    protected $table = 'tbl_matches';

    protected $primaryKey = 'match_id';

    protected $guarded = ['match_id'];

    public $timestamps = false;

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'away_team');
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'home_team');
    }

    public function result(): HasOne
    {
        return $this->hasOne(ResultModel::class, 'match_id');
    }

    public function live(): HasOne
    {
        return $this->hasOne(LiveMatchModel::class, 'match_id');
    }

    /** Knock-out matches can be decided on penalties; group and league games cannot. */
    public function allowsPenalties(): bool
    {
        return $this->match_stage !== null
            && ! in_array($this->match_stage, [self::GROUP_STAGE, 'Friendly'], true);
    }
}
