<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class RoleDashboardController extends Controller
{
    public function gudang(): View
    {
        $lowStockProducts = Product::query()->where('stock', '<=', 5)->orderBy('stock')->limit(8)->get();

        return view('gudang.index', [
            'productCount' => Product::query()->count(),
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
