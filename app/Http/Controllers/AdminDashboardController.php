<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('admin.index', [
            'userCount' => User::query()->count(),
            'todayTransactionCount' => Transaction::query()->whereDate('created_at', today())->count(),
            'todayRevenue' => Transaction::query()->whereDate('created_at', today())->sum('grand_total'),
            'lowStockProducts' => Product::query()->where('stock', '<=', 5)->orderBy('stock')->limit(5)->get(),
            'recentTransactions' => Transaction::query()->latest()->limit(5)->get(),
        ]);
    }
}
