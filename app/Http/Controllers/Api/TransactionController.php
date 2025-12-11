<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit') ? $request->query('limit') : 10;
        $user = auth()->user();
        $relations = ['transactionType:id,name,code,action,thumbnail',
            'paymentMethod:id,name,code,thumbnail'];

        $transactions = Transaction::with($relations)
            ->where('user_id', $user->id)
            ->where('status', 'success')
            ->orderBy('id', 'desc')
            ->paginate($limit);

        $transactions->getCollection()->transform(function ($item) {
            // Fix Payment Method Thumbnail
            if ($item->paymentMethod) {
                $thumbnail = $item->paymentMethod->thumbnail;

                // Check if thumbnail is not empty and not already a full URL
                if ($thumbnail && !filter_var($thumbnail, FILTER_VALIDATE_URL)) {
                    $item->paymentMethod->thumbnail = url('banks/' . $thumbnail);
                } elseif (!$thumbnail) {
                    $item->paymentMethod->thumbnail = null;
                }
            }

            // Fix Transaction Type Thumbnail
            if ($item->transactionType) {
                $thumbnail = $item->transactionType->thumbnail;

                // Check if thumbnail is not empty and not already a full URL
                if ($thumbnail && !filter_var($thumbnail, FILTER_VALIDATE_URL)) {
                    $item->transactionType->thumbnail = url('transaction-type/' . $thumbnail);
                } elseif (!$thumbnail) {
                    $item->transactionType->thumbnail = null;
                }
            }

            return $item;
        });

        return response()->json($transactions);
    }
}
