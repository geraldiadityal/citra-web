<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CitraPartner;
use App\Models\RoomChat;
use App\Models\Transaction;
use Carbon\Carbon;
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

        $partner_id = $request->input('partner_id');
        $status = $request->input('status');
        $paymentUrl = $request->input('payment_url');

        if ($id) {
            $transaction = Transaction::with(['partner', 'user',])->find($id);

            if ($transaction) {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data transaksi tidak ada', 404);
            }
        }

        $transaction = Transaction::with(['partner', 'user', 'session'])->where('user_id', Auth::user()->id);


        if ($partner_id) {
            $transaction->where('partner_id', $partner_id);
        }
        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success($transaction->get(), 'Data list transaksi berhasil diambil');
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
            'partner_id' => 'required|exists:citra_partners,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'total' => 'required',
        ]);
        $now = Carbon::now();
        $partner = CitraPartner::find($request->partner_id);
        if ($now->lessThan(Carbon::parse($partner->active_at))) {
            return ResponseFormatter::error('Maaf, Partner sedang sibuk, Silahkan konsultasi dengan konsultan lain', 'Transaksi gagal');
        }



        //create transaksi
        $transaction = Transaction::create([
            'partner_id' => $request->partner_id,
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
        $transaction = Transaction::with(['partner', 'user'])->find($transaction->id);

        $now->addMinutes(15);
        $partner->active_at = $now;




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
            $partner->active_at = $now;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();
            $partner->save();
            //Return Data ke API
            return ResponseFormatter::success($transaction, 'Transaksi berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi gagal');
        }
    }
}
