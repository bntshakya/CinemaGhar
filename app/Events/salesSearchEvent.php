<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class salesSearchEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $notification;
    public $user;
    public $conversationId;
    public function __construct($user, $notification)
    {

        //
        $this->user = $user;
        $this->notification = $notification;
        $this->conversationId = 1;
        // dd($this->user->email);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): Channel
    // {

    //     // error_log($this->user->email);
    //     // \Log::info($this->user->email);
    //     // var_dump($this->user->email);
    //     // return new Channel('abc');
    //     // dd('private-'.$this->user->email);
    //     // return new PrivateChannel($this->user->email);

    //     Log::channel('custom')->info('@ the privatechannel');
    //     // return new PrivateChannel('abc');
    //     return new Channel('movie-revenue');

    //     // event(new MovieRevenueUpdated());


    // }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    // public function broadcastAs()
    // {
    //     return 'movie.revenue.updated';
    // }

    public function broadcastOn():PrivateChannel
    {
        Log::channel('custom')->info('@ the privatechannel test');
        return new PrivateChannel('movie-revenue');
    }
}
