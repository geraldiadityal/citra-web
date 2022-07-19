<?php

namespace App\Http\Controllers\API;

use App\Events\PrivateChatEvent;
use App\Events\SessionChatEvent;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Chats;
use App\Models\SessionChats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function fetch(Request $request)
    {
        $request->validate(
            [
                'session_chat_id' => 'required|exists:session_chats,id'
            ]
        );

        $session_chat_id = $request->input('session_chat_id');

        $chat = Chats::with(['message'])->where('session_chat_id', $session_chat_id)->get();

        return ResponseFormatter::success($chat, 'Data chat berhasil diambil');
    }

    public function send(SessionChats $sessionChats, Request $request)
    {
        $message = $sessionChats->messages()->create([
            'content' => $request->message
        ]);

        $chat = $message->createForSender($sessionChats->id, Auth::user()->id);
        broadcast(new SessionChatEvent(Auth::user()->id));

        //create and broadcast for receiver
        $message->createForReceiver($sessionChats->id, $request->to_user);
        broadcast(new SessionChatEvent($request->to_user));

        $sessionChats->touch();

        broadcast(new PrivateChatEvent($message->content, $chat));


        return ResponseFormatter::success($chat->session_chat_id, 'Berhasil');
    }
}
