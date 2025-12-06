<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function update() {

        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $type = $notif->payment_type;
        $transactionCode = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        DB::beginTransaction();

        try {
            $status = null;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // TODO set transaction status on your database to 'challenge'
                    // and response with 200 OK
                    $status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    // TODO set transaction status on your database to 'success'
                    $status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                // TODO set transaction status on your database to 'success'
                $status = 'success';
            } else if ($transactionStatus == 'pending') {
                // TODO set transaction status on your database to 'pending'
                $status = 'pending';
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel'){
                // TODO set transaction status on your database to 'failed'
                $status = 'failed';
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)->first();

            if ($transaction->status != 'success') {
                $transactionMount = $transaction->amount;
                $userId = $transaction->user_id;

                $transaction->update(['status' => $status]);

                if ($status == 'success') {
                    Wallet::where('user_id', $userId)->increment('balance', $transactionMount);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Transaction updated'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
