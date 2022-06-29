<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\RoomChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomChatController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');

        $users_id = $request->input('user_id');
        $partners_id = $request->input('partner_id');
        $status = $request->input('status');

        if ($id) {
            $chats = RoomChat::with(['user', 'partner'])->find($id);

            if ($chats) {
                return ResponseFormatter::success($chats, 'Data chat berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data chat tidak ada', 404);
            }
        }

        $chats = RoomChat::with(['user', 'partner',])->where('users_id', Auth::user()->id);

        if ($users_id) {
            $chats->where('users_id', $users_id);
        }
        if ($partners_id) {
            $chats->where('partners_id', $partners_id);
        }
        if ($status) {
            $chats->where('status', 'like', '%' . $status . '%');
        }

        return ResponseFormatter::success($chats->paginate($limit), 'Data chats berhasil diambil');
    }

    public function create(Request $request)
    {
        $request->validate([
            'partners_id' => 'required|exists:citra_partners,id',
            'users_id' => 'required|exists:users,id',
            'status' => 'required',
        ]);

        $room = RoomChat::create([
            'partners_id' => $request->partners_id,
            'users_id' => $request->users_id,
            'status' => $request->status,
        ]);

        $room = RoomChat::with(['user', 'partner'])->find($room->id);

        return ResponseFormatter::success([
            'room' => $room
        ], 'Room berhasil dibuat');
    }
}
