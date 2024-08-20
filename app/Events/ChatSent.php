<?php

namespace App\Events;

use App\Models\register;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $msginstance;
    public $userid;
    public $validated;
    public $username;
    public function __construct($validated,$msginstance,$userid)
    {
        //
        $this->validated = $validated;
        $this->msginstance = $msginstance;
        $this->userid=$userid;
        $this->username = (register::find($userid)->username);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn():Channel
    {
        return new Channel('test');
    }
}
