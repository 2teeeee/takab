<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // جستجو
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'LIKE', "%{$request->search}%")
                            ->orWhere('mobile', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // فیلتر وضعیت
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product', 'user');

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'وضعیت سفارش با موفقیت تغییر کرد.');
    }
}
