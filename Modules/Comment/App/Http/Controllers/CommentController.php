<?php

namespace Modules\Comment\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function add(Request $request)
    {
        $data = [
            'comment' => $request->input('comment'),
            'user_name' => session('username'),
        ];
        $userid = session('id');
        $moviename = $request->input('moviename');
        $comment = $request->input('comment');
        comment::create(['user_id'=>$userid,'movie_name'=>$moviename,'comment'=>$comment,'user_name'=>session('username')]);
        return response()->json($data, 200); // 200 is the HTTP status code

    }
}
