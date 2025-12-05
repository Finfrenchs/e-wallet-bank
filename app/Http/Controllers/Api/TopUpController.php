<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TopUpController extends Controller
{
    public function store(Request $request) {
        $data = $request->only('amount', 'pin', 'payment_method_code');

        $validator = Validator::make($data, [
            'amount' => 'required|integer|min:10000',
            'pin' => 'required|digits:6',
            'payment_method_code' => 'required|in:bni_va,bca_va,bri_va',
        ]);

        //cek validator error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        $pinChecker = pinChecker($request->pin);
        if (!$pinChecker) {
            return response()->json(['message' => 'Invalid PIN'], 403);
        }

        $transactionType = TransactionType::where('code', 'top_up')->first();
        if (!$transactionType) {
            return response()->json(['message' => 'Transaction type not found'], 404);
        }

        $paymentMethod = PaymentMethod::where('code', $request->payment_method_code)->first();
        if (!$paymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        DB::beginTransaction(); // start transaction

        try {
            //create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_type_id' => $transactionType->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $request->amount,
                'transaction_code' => 'TOPUP-' . Str::upper(Str::random(10)),
                'description' => 'Top-up via ' . $paymentMethod->name,
                'status' => 'pending',
            ]);

            $params = $this->buildMidtransParameters([
                'transaction_code' => $transaction->transaction_code,
                'amount' => $transaction->amount,
                'payment_method' => $paymentMethod->code,
            ]);

            $midtrans = $this->callMidtrans($params);

            DB::commit(); // commit transaction

            // return response()->json([
            //     'message' => 'Top-up request created successfully',
            //     'transaction' => $transaction,
            //     'midtrans' => $midtrans
            // ], 201);
            return response()->json($midtrans, 201);

        } catch (\Exception $e) {
            DB::rollBack(); // rollback transaction
            return response()->json(['message' => 'Top-up request failed', 'error' => $e->getMessage()], 500);
        }
    }

    //Call Midtrans
    private function callMidtrans(array $params) {

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds');

        $createTransaction = \Midtrans\Snap::createTransaction($params);

        return [
            'redirect_url' => $createTransaction->redirect_url,
            // 'transaction_id' => $createTransaction->transaction_id,
            'token' => $createTransaction->token,
        ];
    }

    private function buildMidtransParameters( array $params ) {
        $user = auth()->user();
        $splitName = $this->splitName($user->name);

        $transactionDetails = [
                'order_id' => $params['transaction_code'],
                'gross_amount' => $params['amount'],
        ];
        $customerDetails = [
                'first_name' => $splitName['first_name'],
                'last_name' => $splitName['last_name'],
                'email' => $user->email,
        ];
        $enabledPayments = [$params['payment_method']];
        // $vtweb = [];

        $midtransParams = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => $enabledPayments,
                // 'vtweb' => $vtweb,
        ];

        return $midtransParams;
    }

    private function splitName($fullName) {
        $name = explode(' ', $fullName);
        $firstName = implode(' ', array_slice($name, 0, 1));
        $lastName = count($name) > 1 ? implode(' ', array_slice($name, 1)) : '';

        return ['first_name' => $firstName, 'last_name' => $lastName];
    }
}
