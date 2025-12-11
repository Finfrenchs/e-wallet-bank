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
                $thumbnail = $item->thumbnail;

                // Check if thumbnail is not empty and not already a full URL
                if ($thumbnail && !filter_var($thumbnail, FILTER_VALIDATE_URL)) {
                    $item->thumbnail = url('banks/' . $thumbnail);
                } elseif (!$thumbnail) {
                    $item->thumbnail = null;
                }
                // If already a full URL, don't modify it

                return $item;
            });

        return response()->json($paymentMethods);
    }
}
