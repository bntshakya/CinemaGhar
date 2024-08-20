<?php

namespace Modules\Chat\App\Http\Controllers;


use App\Events\ChatSent;
use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\ChatMessage;
use App\Models\register;
use App\Models\TrackMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function addchat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|',
        ]);

        // Create the AdminMessage
        // AdminMessage::create(['message' => $validated['message']]);

        $msg_instance = [
            'type' => 'admin',
            'msg' => $validated['message'],
        ];
        $userid = $request['userid'];
        if (is_null($userid)) {
            return redirect()->back()->with('failure', 'Message creation failed!');
        }

        ChatSent::dispatch($validated, $msg_instance, $userid);
        return response()->json(
            $data = ['status' => 'message sent'],
            $status = 200
        );
    }

    public function userchat()
    {
        $user_id = session('id');
        $tracked_list = TrackMessage::where('user_id', $user_id)->value('msg_list');
        if (is_null($user_id) or is_null($tracked_list)) {
            $tracked_list = [['type' => 'admin', 'msg' => 'begin conversation']];
        }
        return view('chat', ['users' => register::all(), 'tracklist' => $tracked_list, 'user_id' => $user_id]);
    }

    public function addchatuser(Request $request)
    {
     
        $validated = $request->validate([
            'message' => 'required|string|',
        ]);

        // Create the AdminMessage
        $userid = session('id');
        // ChatMessage::create(['user_id' => $userid, 'message' => $validated['message']]);
        $msg_instance = [
            'type' => 'user',
            'msg' => $validated['message'],
        ];

        ChatSent::dispatch($validated, $msg_instance, $userid);
        
        return response()->json(
            $data = ['status' => 'message sent'],
            $status = 200
        );
    }

}
