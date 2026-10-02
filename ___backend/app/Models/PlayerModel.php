<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerModel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tbl_players';

    protected $primaryKey = 'player_id';

    protected $guarded = ['player_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(TeamModel::class, 'team_id');
    }

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }
}
