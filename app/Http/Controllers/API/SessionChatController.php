<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\SessionChats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionChatController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');

        if ($id) {
            $session = SessionChats::with(['chats', 'messages', 'transaction'])->find($id);

            if ($session) {
                return ResponseFormatter::success($session, 'Data transaksi berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data transaksi tidak ada', 404);
            }
        }

        $session = SessionChats::with(['chats' => function ($query) {
            return $query->orderBy('id', 'desc')->limit(1);
        }, 'messages' => function ($query) {
            return $query->orderBy('id', 'desc')->limit(1);
        }, 'transaction'])
            ->where('user1_id', Auth::user()->id)
            ->orWhere('user2_id', Auth::user()->id)->get();



        return ResponseFormatter::success($session, 'Data Sesi room chat berhasil diambil');
    }
}
