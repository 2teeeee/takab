<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WholesalerSaleController extends Controller
{
    public function index(Request $request): View
    {
        $wholesalerId = auth()->id();

        $query = Order::with([
            'user',
            'items.product.translation',
        ])
            ->where('wholesaler_id', $wholesalerId)
            ->where('seller_role', 'wholesaler')
            ->latest();

        // جستجو
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', $search)

                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");
                    });
            });
        }

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('wholesaler.sales.index', compact('orders'));
    }
}