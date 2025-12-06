<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class RedirectPaymentController extends Controller
{
    public function finish(Request $request){
        $transaction_code = $request->order_id;
        $transaction = Transaction::where('transaction_code', $transaction_code)->first();

        return view('payment-finish', ['transaction' => $transaction]);
    }
}
