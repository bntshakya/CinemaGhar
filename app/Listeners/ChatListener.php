<?php

namespace App\Listeners;

use App\Events\ChatSent;
use App\Models\AdminMessage;
use App\Models\TrackMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     */
    public function handle(ChatSent $event): void
    {
        //
        $validated = $event->validated;
        $msg_instance = $event->msginstance;
        $userid = $event->userid;
        // AdminMessage::create(['message' => $validated['message']]);
        $exists = TrackMessage::where('user_id', $userid)->exists();
        if ($exists) {
            $msg_list = TrackMessage::where('user_id', $userid)->value('msg_list');
            array_push($msg_list, $msg_instance);
            TrackMessage::where('user_id', $userid)->update(['msg_list' => json_encode($msg_list)]);
        } else {
            TrackMessage::create(['user_id' => $userid, 'msg_list' => [$msg_instance]]);
        }
    }
}
