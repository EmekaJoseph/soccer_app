<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Standings_LeagueModel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tbl_standings_league';

    protected $primaryKey = 'standing_id';

    protected $guarded = ['standing_id'];

    protected $attributes = [
        'played' => 0,
        'won' => 0,
        'draw' => 0,
        'lose' => 0,
        'goal_diff' => 0,
        'points' => 0,
    ];

    protected function casts(): array
    {
        return [
            'played' => 'integer',
            'won' => 'integer',
            'draw' => 'integer',
            'lose' => 'integer',
            'goal_diff' => 'integer',
            'points' => 'integer',
        ];
    }

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'team_id');
    }
}
