<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


use Illuminate\Support\Facades\DB;


use App\Models\TeamModel;


class liveScore implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liveMatches;
    public $tour_id = '';

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        // $liveUpdates = DB::table('tbl_live')->where('tour_id', $tour_id)->get();
        // if (sizeof($liveUpdates) > 0) {
        //     foreach ($liveUpdates as $result) {
        //         $result->home_team = (TeamModel::find($result->home_team))->team_name;
        //         $result->away_team = (TeamModel::find($result->away_team))->team_name;
        //     }
        // }

        $this->liveMatches = 'liveMatches from websockect';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('liveMatches'),
        ];
    }
}
