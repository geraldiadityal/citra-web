<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CitraPartner;
use App\Models\RoomChat;
use App\Models\SessionChats;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        //set config midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //create instance midtrans notif
        $notification = new Notification();

        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        //search transaksi by ID
        $transaction = Transaction::findOrFail($order_id);
        $partner = CitraPartner::findOrFail($transaction->partner_id);
        $hours = ($transaction->total) / ($partner->price);
        $now = Carbon::now();
        $now->addHours($hours);


        // $room = RoomChat::findOrFail($transaction->room_id);

        //handle notifikasi status
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transaction->status = 'PENDING';
                } else {
                    $transaction->status = 'SUCCESS';

                    $session = SessionChats::create([
                        'user1_id' => $transaction->user_id,
                        'user2_id' => $partner->users_id,
                        'expire_at' => $now,
                    ]);
                    $transaction->session_chat_id = $session->id;

                    $partner->active_at = $now;
                }
            }
        } else if ($status == 'settlement') {
            $transaction->status = 'SUCCESS';

            $session = SessionChats::create([
                'user1_id' => $transaction->user_id,
                'user2_id' => $partner->users_id,
                'expire_at' => $now,
            ]);

            $transaction->session_chat_id = $session->id;
            $partner->active_at = $now;
        } else if ($status == 'pending') {
            $transaction->status = 'PENDING';
        } else if ($status == 'deny') {
            $transaction->status = 'CANCELLED';
        } else if ($status == 'expire') {
            $transaction->status = 'EXPIRE';
        } else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }

        //save transaksi
        $transaction->save();
        $partner->save();
    }

    public function success()
    {
        return view('midtrans.success');
    }
    public function unfinish()
    {
        return view('midtrans.unfinish');
    }
    public function error()
    {
        return view('midtrans.error');
    }
}
