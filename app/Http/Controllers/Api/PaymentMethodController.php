<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::where('status', 'active')
            ->where('code', '!=', 'lwk')
            ->get()
            ->map(function ($item) {
                $item->thumbnail = $item->thumbnail ? url('banks/'.$item->thumbnail) : "";
                return $item;
            });

        return response()->json($paymentMethods);
    }
}
