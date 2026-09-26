<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class RoleDashboardController extends Controller
{
    public function owner(): View
    {
        return view('owner.index', $this->metrics());
    }

    public function gudang(): View
    {
        return view('gudang.index', $this->metrics());
    }

    /** @return array<string, mixed> */
    private function metrics(): array
    {
        return [
            'userCount' => User::query()->count(),
            'productCount' => Product::query()->count(),
            'todayTransactionCount' => Transaction::query()->whereDate('created_at', today())->count(),
            'todayRevenue' => Transaction::query()->whereDate('created_at', today())->sum('grand_total'),
            'lowStockProducts' => Product::query()->where('stock', '<=', 5)->orderBy('stock')->limit(8)->get(),
            'recentTransactions' => Transaction::query()->latest()->limit(8)->get(),
        ];
    }
}
