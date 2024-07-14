<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MatchModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUlids;

    protected $table = 'tbl_matches';
    protected $primaryKey = 'match_id';
    protected $guarded = [];

    public $timestamps = false;

    public function relatedTournament()
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(TeamModel::class, 'away_team');
    }

    public function homeTeam()
    {
        return $this->belongsTo(TeamModel::class, 'home_team');
    }

    public function result()
    {
        return $this->hasOne(ResultModel::class, 'match_id');
    }
}
