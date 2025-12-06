<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransferHistory;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only('amount', 'pin', 'send_to');

        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:10000',
            'pin' => 'required|digits:6',
            'send_to' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $sender = auth()->user();
        $receiver = User::select('users.id', 'users.username')
            ->join('wallets', 'wallets.user_id', 'users.id')
            ->where('users.username', $request->send_to)
            ->orWhere('wallets.card_number', $request->send_to)
            ->first();
        
        $pinChecker = pinChecker($request->pin);
        if (!$pinChecker) {
            return response()->json(['message' => 'Invalid PIN'], 400);
        }

        if (!$receiver) {
            return response()->json(['message' => 'Recipient not found'], 404);
        }

        //check if send to self
        if ($sender->id === $receiver->id) {
            return response()->json(['message' => 'You cannot send money to yourself'], 400);
        }

        //check sender balance
        $senderWallet = Wallet::where('user_id', $sender->id)->first();
        if ($senderWallet->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        DB::beginTransaction();

        try {
            $transactionType = TransactionType::whereIn('code', ['receive', 'transfer'])
                ->orderBy('code', 'asc')
                ->get();
            
            $receiveTransactionType = $transactionType->first();
            $transferTransactionType = $transactionType->last();

            $transactionCode = 'TRANSFER-' . Str::upper(Str::random(10));
            $paymentMethod = PaymentMethod::where('code', 'lwk')->first();

            // transaction for transfer
            $transferTransaction = Transaction::create([
                'user_id' => $sender->id,
                'transaction_type_id' => $transferTransactionType->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $request->amount,
                'status' => 'success',
                'transaction_code' => $transactionCode,
                'description' => 'Transfer to ' . $receiver->username
            ]);

            // update sender balance
            $senderWallet->decrement('balance', $request->amount);

            // transaction for receive
            $receiveTransaction = Transaction::create([
                'user_id' => $receiver->id,
                'transaction_type_id' => $receiveTransactionType->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $request->amount,
                'status' => 'success',
                'transaction_code' => $transactionCode,
                'description' => 'Receive from ' . $sender->username
            ]);

            // update receiver balance
            Wallet::where('user_id', $receiver->id)->increment('balance', $request->amount);

            TransferHistory::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'transaction_code' => $transactionCode,
            ]);

            DB::commit();
            return response()->json(['message' => 'Transfer successful'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
