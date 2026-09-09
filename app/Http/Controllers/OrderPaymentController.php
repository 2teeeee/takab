<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderPaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return view('payment.success', [
                'order' => $order,
                'ref_id' => $order->reference_id,
            ]);
        }

        return view('profile.orders.payment', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return view('payment.success', [
                'order' => $order,
                'ref_id' => $order->reference_id,
            ]);
        }

        return redirect()->route('zarinpal.pay', [
            'order' => $order,
        ]);
    }
}