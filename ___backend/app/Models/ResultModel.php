<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultModel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tbl_results';

    protected $primaryKey = 'result_id';

    protected $guarded = ['result_id'];

    protected function casts(): array
    {
        return [
            'home_score' => 'integer',
            'away_score' => 'integer',
            'home_score_pen' => 'integer',
            'away_score_pen' => 'integer',
        ];
    }

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function relatedMatch(): BelongsTo
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    /** Team id of the winner (penalties decide drawn knock-out games), or '' for a draw. */
    public function winnerId(): string
    {
        if ($this->home_score !== $this->away_score) {
            return $this->home_score > $this->away_score ? $this->home_team : $this->away_team;
        }

        if ($this->home_score_pen !== null && $this->away_score_pen !== null
            && $this->home_score_pen !== $this->away_score_pen) {
            return $this->home_score_pen > $this->away_score_pen ? $this->home_team : $this->away_team;
        }

        return '';
    }
}
