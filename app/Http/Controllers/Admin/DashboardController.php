<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Users
        $totalUsers = User::count();

        // Total Transactions
        $totalTransactions = Transaction::count();

        // Total Revenue (sum of all successful transactions)
        $totalRevenue = Transaction::where('status', 'success')->sum('amount');

        // Pending Transactions
        $pendingTransactions = Transaction::where('status', 'pending')->count();

        // Recent Transactions (last 10)
        $recentTransactions = Transaction::with([
            'paymentMethod:id,name,code,thumbnail',
            'user:id,name',
            'transactionType:id,code,action',
        ])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

        // Transactions by Type (for chart)
        $transactionsByType = Transaction::join('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
            ->select('transaction_types.code', DB::raw('count(*) as total'))
            ->groupBy('transaction_types.code')
            ->get();

        // Transactions by Status
        $transactionsByStatus = Transaction::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Monthly Revenue (last 6 months)
        $monthlyRevenue = Transaction::where('status', 'success')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        return view('dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'totalRevenue',
            'pendingTransactions',
            'recentTransactions',
            'transactionsByType',
            'transactionsByStatus',
            'monthlyRevenue'
        ));
    }
}
