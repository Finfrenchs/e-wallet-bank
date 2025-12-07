<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperatorCard;

class OperatorController extends Controller
{
    public function index(Request $request)
    {

        $limit = $request->query('limit') ? $request->query('limit') : 10;

        $operatorCards = OperatorCard::with('dataPlans')
            ->where('status', 'active')
            ->paginate($limit);

        $operatorCards->getCollection()->transform(function ($operatorCard) {
            $operatorCard->thumbnail = $operatorCard->thumbnail ? url($operatorCard->thumbnail) : "";
            return $operatorCard;
        });

        return response()->json($operatorCards);
    }
}
