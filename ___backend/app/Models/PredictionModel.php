<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A fan's guess at the top three of a tournament. Contains personal data
 * (name, phone, IP) so it is only ever exposed to the tournament owner.
 */
class PredictionModel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tbl_prediction';

    protected $primaryKey = 'prediction_id';

    protected $guarded = ['prediction_id'];

    protected $hidden = ['device_ip'];

    public $timestamps = false;

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }
}
