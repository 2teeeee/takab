<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\CommissionService;
use App\Services\WalletService;
use App\Services\ZarinpalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZarinpalController extends Controller
{
    protected CartService $cartService;
    protected CommissionService $commissionService;
    protected WalletService $walletService;

    public function __construct(
        CartService $cartService,
        CommissionService $commissionService,
        WalletService $walletService
    ) {
        $this->cartService = $cartService;
        $this->commissionService = $commissionService;
        $this->walletService = $walletService;
    }

    public function pay(Order $order, ZarinpalService $zarinpal)
    {
        if ($order->payment_status === 'paid') {
            return redirect()
                ->back()
                ->withErrors([
                    'error' => 'این سفارش قبلاً پرداخت شده است.'
                ]);
        }

        try {

            $result = $zarinpal->requestPayment(
                amount: $order->final_total * 10,
                callbackUrl: route('zarinpal.callback'),
                description: "پرداخت سفارش شماره {$order->id}",
                metadata: [
                    'order_id' => (string) $order->id,
                ]
            );

            $order->authority = $result['authority'];
            $order->payment_status = 'pending';
            $order->save();

            return redirect()->away(
                $result['payment_url']
            );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function callback(
        Request $request,
        ZarinpalService $zarinpal
    ) {
        $authority = $request->query('Authority');
        $status = $request->query('Status');

        $order = Order::query()
            ->where('authority', $authority)
            ->firstOrFail();

        /*
         * اگر callback دوباره ارسال شده باشد،
         * نباید دوباره کیف پول شارژ و تخلیه شود.
         */
        if ($order->payment_status === 'paid') {
            return view('payment.success', [
                'order' => $order,
                'ref_id' => $order->reference_id,
            ]);
        }

        /*
         * پرداخت توسط کاربر لغو شده
         */
        if ($status !== 'OK') {

            $order->status = 'failed';
            $order->payment_status = 'unpaid';
            $order->save();

            return view('payment.failed', [
                'order' => $order,
                'error' => 'پرداخت توسط کاربر لغو شد.'
            ]);
        }

        try {
            $result = $zarinpal->verifyPayment(
                amount: $order->final_total * 10,
                authority: $authority
            );

            DB::transaction(function () use (
                $order,
                $result
            ) {

                $lockedOrder = Order::query()
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                if ($lockedOrder->payment_status === 'paid') {
                    return;
                }

                $this->walletService->receiveAndPay(
                    user: $order->user,
                    amount: $order->final_total,
                    source: 'online',
                    reference: $order
                );

                $lockedOrder->status = 'paid';
                $lockedOrder->payment_status = 'paid';
                $lockedOrder->reference_id = $result['ref_id'];
                $lockedOrder->save();

                $this->commissionService
                    ->createForOrder($lockedOrder);

                $this->commissionService
                    ->payOrderCommissionsToWallet($lockedOrder);
            });

            $this->commissionService->sendCommissionSms($order);

            $this->cartService->clear();

            return view('payment.success', [
                'order' => $order,
                'ref_id' => $result['ref_id'],
            ]);

        } catch (\Exception $e) {

            if ($order->payment_status !== 'paid') {
                $order->payment_status = 'unpaid';
                $order->save();
            }

            return view('payment.failed', [
                'order' => $order,
                'error' => $e->getMessage(),
            ]);

        }
    }
}
