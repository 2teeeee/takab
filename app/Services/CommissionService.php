<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ReferralCommission;
use App\Services\Sms\NikSmsService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CommissionService
{
    public function __construct(
        protected NikSmsService $sms
    ) {
    }

    /**
     * Create all commissions related to an order.
     *
     * Commission types:
     * - wholesaler
     * - store
     * - referral
     * - customer_discount
     */
    public function createForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {

            // Prevent duplicate commissions.
            if ($order->commissions()->exists()) {
                return;
            }

            /*
             * 1. Wholesaler commission
             *
             * The wholesaler is the owner of the inventory
             * from which the customer eventually received the product.
             */
            $this->createWholesalerCommission($order);

            /*
             * 2. Store commission
             *
             * The seller/store related to the order receives
             * a fixed commission per product.
             */
            $this->createStoreCommission($order);

            /*
             * 3. Referral commission
             *
             * The referrer receives a fixed commission
             * when a valid referral exists.
             */
            $this->createReferralCommission($order);

            /*
             * 4. Customer discount
             *
             * The customer receives the referral discount.
             *
             * This is recorded for accounting/reporting purposes,
             * but it is not a payable commission.
             */
            $this->createCustomerDiscount($order);
        });
    }

    protected function createWholesalerCommission(Order $order): void
    {
        if (!$order->wholesaler_id) {
            return;
        }

        $wholesaler = $order->wholesaler;

        if (!$wholesaler) {
            return;
        }

        if (!$wholesaler->hasRole('wholesaler')) {
            return;
        }

        $amount = $this->calculateCommissionAmount($order);

        if ($amount <= 0) {
            return;
        }

        $this->createCommission(
            order: $order,
            userId: $wholesaler->id,
            type: 'wholesaler',
            amount: $amount,
            note: __('commissions.notes.wholesaler')
        );
    }

    protected function createStoreCommission(Order $order): void
    {
        if (!$order->seller_id) {
            return;
        }

        $seller = $order->seller;

        if (!$seller) {
            return;
        }

        if (!$seller->hasRole('seller')) {
            return;
        }

        $amount = $this->calculateCommissionAmount($order);

        if ($amount <= 0) {
            return;
        }

        $this->createCommission(
            order: $order,
            userId: $seller->id,
            type: 'store',
            amount: $amount,
            note: __('commissions.notes.store')
        );
    }

    protected function createReferralCommission(Order $order): void
    {
        if (!$order->moaref_id) {
            return;
        }

        $referrer = $order->moaref;

        if (!$referrer) {
            return;
        }

        $amount = $this->calculateCommissionAmount($order);

        if ($amount <= 0) {
            return;
        }

        $this->createCommission(
            order: $order,
            userId: $referrer->id,
            type: 'referral',
            amount: $amount,
            note: __('commissions.notes.referral')
        );
    }

    protected function createCustomerDiscount(Order $order): void
    {
        /*
         * The customer discount should only be recorded
         * when an actual referral is associated with the order.
         */
        if (!$order->moaref_id) {
            return;
        }

        if (!$order->user_id) {
            return;
        }

        $amount = $order->discount;

        if ($amount <= 0) {
            return;
        }

        $this->createCommission(
            order: $order,
            userId: $order->user_id,
            type: 'customer_discount',
            amount: $amount,
            note: __('commissions.notes.customer_discount'),
            isPaid: true
        );
    }

    /**
     * Calculate commission based on the number of ordered products.
     *
     * Each product unit generates a 1,000,000 Toman commission.
     */
    protected function calculateCommissionAmount(Order $order): int
    {
        $quantity = $order->items()->sum('quantity');

        return $quantity * 1_000_000;
    }

    /**
     * Store a commission record.
     */
    protected function createCommission(
        Order $order,
        int $userId,
        string $type,
        int $amount,
        ?string $note = null,
        bool $isPaid = false
    ): ReferralCommission {

        return $order->commissions()->create([
            'user_id'   => $userId,
            'type'      => $type,
            'amount'    => $amount,
            'is_paid'   => $isPaid,
            'paid_at'   => $isPaid ? now() : null,
            'paid_by'   => $isPaid ? auth()->id() : null,
            'note'      => $note,
        ]);
    }

    /**
     * Send SMS to users who received commission from the order.
     */
    public function sendCommissionSms(Order $order): void
    {
        $commissions = ReferralCommission::query()
            ->with('user')
            ->where('order_id', $order->id)
            ->where('type', '!=', 'customer_discount')
            ->get();

        /*
         * Group commissions by user.
         * If one user has multiple commissions,
         * only one SMS will be sent.
         */
        $commissions
            ->filter(fn ($commission) =>
                $commission->user &&
                !empty($commission->user->mobile)
            )
            ->groupBy('user_id')
            ->each(function ($userCommissions) use ($order) {

                $user = $userCommissions->first()->user;

                $message = $this->commissionMessage(
                    $userCommissions,
                    $order
                );

                $this->sms->sendSingle(
                    $user->mobile,
                    $message
                );
            });
    }

    protected function commissionMessage(
        $commissions,
        Order $order
    ): string {

        $lines = [];
        $total = 0;

        foreach ($commissions as $commission) {

            $amount = (int) $commission->amount;
            $total += $amount;

            $label = match ($commission->type) {

                'wholesaler' => 'کمیسیون عمده‌فروشی',

                'store' => 'کمیسیون فروشگاه',

                'referral' => 'کمیسیون معرفی',

                default => 'کمیسیون',
            };

            $lines[] = "{$label}: "
                . number_format($amount)
                . " تومان";
        }

        $totalFormatted = number_format($total);

        return "کاربر گرامی،\n"
            . "کمیسیون‌های شما بابت سفارش شماره {$order->id} ثبت شد.\n"
            . "\nمبلغ: {$totalFormatted} تومان";
    }
}