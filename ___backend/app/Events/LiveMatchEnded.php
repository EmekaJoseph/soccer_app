<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcast on the public "tournament.{tour_id}" channel that the stats page listens to.
 */
class LiveMatchEnded implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(
        public readonly string $tour_id,
        public readonly int $live_id,
        public readonly array $results = [],
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('tournament.'.$this->tour_id)];
    }

    public function broadcastAs(): string
    {
        return 'live.ended';
    }
}
