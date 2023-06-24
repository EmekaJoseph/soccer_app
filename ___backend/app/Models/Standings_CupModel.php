<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Standings_CupModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasUlids;

    protected $table = 'tbl_standings_cup';
    protected $primaryKey = 'standing_id';
    protected $guarded = [];

    public function relatedTournament()
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }
}
