<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class TournamentModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUlids;

    protected $table = 'tbl_tournament';
    protected $primaryKey = 'tour_id';
    protected $guarded = [];

    public function relatedTeams()
    {
        return $this->hasMany(TeamModel::class, 'tour_id');
    }

    // public function relatedSchedules()
    // {
    //     return $this->hasMany(MatchModel::class, 'tour_id');
    // }

    // public function relatedStandings()
    // {
    //     return $this->hasMany(StandingsModel::class, 'tour_id');
    // }

    // public function relatedResults()
    // {
    //     return $this->hasMany(ResultModel::class, 'tour_id');
    // }

    // public function relatedUser()
    // {
    //     return $this->belongsTo(UserModel::class, 'user_id');
    // }
}
