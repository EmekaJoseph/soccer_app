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

    public $live_id;
    public $results;

    /**
     * Create a new event instance.
     */
    public function __construct($live_id, $results)
    {
        $this->live_id = $live_id;
        $this->results = $results;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('liveMatch'),
        ];
    }
}
