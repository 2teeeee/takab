<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryTransferService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'LIKE', "%{$request->search}%")
                            ->orWhere('mobile', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if (Auth::user()->hasRole('seller')) {
            $query->where('seller_id', Auth::id());
        }

        $orders = $query
            ->latest()
            ->paginate(15);

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

        $newStatus = $request->status;

        /*
        |--------------------------------------------------------------------------
        | Inventory transfer order
        |--------------------------------------------------------------------------
        */

        if ($order->from_user_id) {

            if ($order->status !== 'pending') {
                return back()->with(
                    'error',
                    __('orders.order_already_processed')
                );
            }

            if (!in_array($newStatus, ['success', 'rejected'])) {
                return back()->with(
                    'error',
                    __('orders.invalid_inventory_status')
                );
            }

            try {

                $service = app(InventoryTransferService::class);

                if ($newStatus === 'success') {
                    $service->approve($order);
                } else {
                    $service->reject($order);
                }

                return back()->with(
                    'success',
                    __('orders.status_updated')
                );

            } catch (\Throwable $e) {

                report($e);

                return back()->with(
                    'error',
                    $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normal order
        |--------------------------------------------------------------------------
        */

        $allowedStatuses = [
            'pending',
            'paid',
            'processing',
            'shipping',
            'delivered',
            'canceled',
        ];

        if (!in_array($newStatus, $allowedStatuses)) {
            return back()->with(
                'error',
                __('orders.invalid_status')
            );
        }

        $order->update([
            'status' => $newStatus,
        ]);

        return back()->with(
            'success',
            __('orders.status_updated')
        );
    }
}
