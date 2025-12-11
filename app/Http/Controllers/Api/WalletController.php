<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $wallet = Wallet::select('pin', 'balance', 'card_number')
            ->where('user_id', $user->id)
            ->first();

        return response()->json($wallet);
    }

    //udate wallet pin
    public function updatePin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'previous_pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!pinChecker($request->input('previous_pin'))) {
            return response()->json(['message' => 'Previous pin is incorrect'], 400);
        }

        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->update([
            'pin' => $request->new_pin,
        ]);

        return response()->json(['message' => 'Wallet pin updated successfully']);
    }
}

