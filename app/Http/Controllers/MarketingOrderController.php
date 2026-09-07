<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReferralCommission;
use Illuminate\View\View;

class MarketingOrderController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $orders = Order::query()
            ->where('moaref_id', $userId)
            ->with('items.product')
            ->latest()
            ->paginate(10);

        $stats = [
            'orders' => Order::query()
                ->where('moaref_id', $userId)
                ->count(),

            'sales' => Order::query()
                ->where('moaref_id', $userId)
                ->sum('final_total'),

            'products' => \App\Models\OrderItem::query()
                ->whereHas('order', function ($query) use ($userId) {
                    $query->where('moaref_id', $userId);
                })
                ->sum('quantity'),

            'commission' => ReferralCommission::query()
                ->where('user_id', $userId)
                ->where('type', 'referral')
                ->whereIn('status', ['pending', 'approved', 'paid'])
                ->sum('amount'),
        ];

        return view(
            'profile.marketing-orders.index',
            compact('orders', 'stats')
        );
    }

    public function show(Order $order): View
    {
        abort_unless(
            $order->moaref_id === auth()->id(),
            403
        );

        $order->load([
            'items.product',
        ]);

        return view(
            'profile.marketing-orders.show',
            compact('order')
        );
    }
}
