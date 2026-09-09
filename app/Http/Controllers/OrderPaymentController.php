<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPaymentController extends Controller
{
    public function show(Order $order)
    {
        return view('profile.orders.payment', compact('order'));
    }

    public function pay(Order $order)
    {
        //TODO: check if is paid redirect end

        return redirect()->route('zarinpal.pay', [
            'order' => $order,
        ]);
    }
}