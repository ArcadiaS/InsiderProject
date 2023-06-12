<?php

namespace App\Events;

use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchPlayedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Competition $competition;

    /**
     * Create a new event instance.
     */
    public function __construct(Competition $competition)
    {
        //
        $this->competition = $competition;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
