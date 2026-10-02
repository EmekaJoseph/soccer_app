<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_feedback';

    protected $primaryKey = 'feedback_id';

    protected $guarded = ['feedback_id'];

    protected $hidden = ['device_ip'];

    public $timestamps = false;

    public function relatedTournament(): BelongsTo
    {
        return $this->belongsTo(TournamentModel::class, 'tour_id');
    }
}
