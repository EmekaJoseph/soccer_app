<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PlayerModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUlids;

    protected $table = 'tbl_players';
    protected $primaryKey = 'player_id';
    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(TeamModel::class, 'team_id');
    }

    public function relatedTournament()
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }
}
