<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\RoomChat;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');

        $users_id = $request->input('user_id');
        $room_id = $request->input('room_id');
        $status = $request->input('status');
        $paymentUrl = $request->input('payment_url');

        if ($id) {
            $transaction = Transaction::with(['room', 'user',])->find($id);

            if ($transaction) {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data transaksi tidak ada', 404);
            }
        }

        $transaction = Transaction::with(['room', 'user',])->where('user_id', Auth::user()->id);


        if ($room_id) {
            $transaction->where('room_id', $room_id);
        }
        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success($transaction->paginate($limit), 'Data list transaksi berhasil diambil');
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->all());

        return ResponseFormatter::success($transaction, 'Transaksi berhasil diperbarui');
    }

    public function checkout(Request $request)
    {

        $request->validate([
            // 'room_id' => 'required|exists:room_chats,id',
            'partners_id' => 'required|exists:citra_partners,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'total' => 'required',
        ]);

        //create room
        $room = RoomChat::create([
            'partners_id' => $request->partners_id,
            'users_id' => $request->user_id,
            'status' => $request->status,
        ]);

        //create transaksi
        $transaction = Transaction::create([
            'room_id' => $room->id,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'total' => $request->total,
            'payment_url' => '',
        ]);


        //Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Call transaksi yang dibuat
        $transaction = Transaction::with(['room', 'user'])->find($transaction->id);

        //Create Transaksi Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'vtweb' => [],
        ];

        //Call Midtrans
        try {
            //get halaman midtrans payment
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();
            //Return Data ke API
            return ResponseFormatter::success($transaction, 'Transaksi berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi gagal');
        }
    }
}
