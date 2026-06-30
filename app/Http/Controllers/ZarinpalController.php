<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\ZarinpalService;
use Illuminate\Http\Request;

class ZarinpalController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function pay(Order $order, ZarinpalService $zarinpal)
    {
        try {
            $result = $zarinpal->requestPayment(
                amount: $order->total * 10,
                callbackUrl: route('zarinpal.callback'),
                description: "پرداخت سفارش شماره {$order->id}",
                metadata: ['order_id' => "$order->id"]
            );

            $order->authority = $result['authority'];
            $order->save();

            return redirect()->away($result['payment_url']);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function callback(Request $request, ZarinpalService $zarinpal)
    {
        $authority = $request->query('Authority');
        $status    = $request->query('Status');

        $order = Order::where('authority', $authority)->firstOrFail();

        if ($status !== 'OK') {
            $order->status = 'failed';
            $order->save();
            return view('payment.failed', ['order' => $order, 'error' => 'پرداخت توسط کاربر لغو شد.']);
        }

        try {
            $result = $zarinpal->verifyPayment(amount: $order->total * 10, authority: $authority);

            $order->status = 'paid';
            $order->reference_id = $result['ref_id'];
            $order->save();

            $this->cartService->clear();

            return view('payment.success', ['order' => $order, 'ref_id' => $result['ref_id']]);

        } catch (\Exception $e) {
            $order->status = 'failed';
            $order->save();
            return view('payment.failed', ['order' => $order, 'error' => $e->getMessage()]);
        }
    }
}
