<?php

namespace App\Http\Controllers\API;

use App\Events\SessionChatEvent;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CitraPartner;
use App\Models\SessionChats;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionChatController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');


        if ($id) {
            $session = SessionChats::with(['chats', 'messages', 'transaction'])->find($id);

            if ($session) {
                return ResponseFormatter::success($session, 'Data transaksi berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data transaksi tidak ada', 404);
            }
        }

        $session = SessionChats::with(['chats' => function ($query) {
            return $query->orderBy('id', 'desc');
        }, 'messages' => function ($query) {
            return $query->orderBy('id', 'desc');
        }, 'transaction', 'user', 'partner'])
            ->where('user1_id', Auth::user()->id)
            ->orWhere('user2_id', Auth::user()->id)->get();



        return ResponseFormatter::success($session, 'Data Sesi room chat berhasil diambil');
    }

    public function endChat(Request $request)
    {
        $status = "FINISH";
        $id = $request->input('id');
        if ($id) {
            $session = SessionChats::with(['chats', 'messages', 'transaction'])->find($id);

            $transaction = Transaction::where('session_chat_id', $id)->first();

            if ($transaction) {
                if ($session) {
                    $partner = CitraPartner::find($transaction->partner_id);

                    $transaction->status = $status;
                    $transaction->save();
                    $session->expire_at = Carbon::now();
                    $session->save();
                    $partner->active_at = Carbon::now();

                    broadcast(new SessionChatEvent($session->user1_id));
                    broadcast(new SessionChatEvent($session->user2_id));

                    return ResponseFormatter::success($session, 'Status berhasil diganti');
                } else {
                    return ResponseFormatter::error(null, 'Data Session tidak ada', 404);
                }
            }
            return ResponseFormatter::error(null, 'Status gagal diganti', 404);
        }
    }
}
