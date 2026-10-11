<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\PurchaseOrder;
use App\Models\PurchaseQuote;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PurchaseQuoteController extends Controller
{
    public function approve(
        PurchaseQuote $purchaseQuote,
        NikSmsService $sms
    ): RedirectResponse {

        $purchaseOrder = DB::transaction(function () use ($purchaseQuote) {

            $purchaseQuote->load([
                'purchaseRequest.productionRequirement',
                'purchaseRequest.componentProduct',
                'purchaseRequest.supplier',
            ]);

            if ($purchaseQuote->status !== 'pending') {
                abort(
                    422,
                    'این پیشنهاد قیمت قبلاً تعیین تکلیف شده است.'
                );
            }

            $request = $purchaseQuote->purchaseRequest;

            if ($request->status !== 'quoted') {
                abort(
                    422,
                    'درخواست خرید در وضعیت قابل تأیید نیست.'
                );
            }

            /*
             * فقط برای درخواست‌های تولید
             */
            if ($request->productionRequirement) {

                if (
                    (float) $request
                        ->productionRequirement
                        ->purchase_quantity <= 0
                ) {
                    abort(
                        422,
                        'برای این قطعه نیازی به خرید وجود ندارد.'
                    );
                }
            }

            /*
             * تأیید پیشنهاد
             */
            $purchaseQuote->update([
                'status' => 'approved',
            ]);

            /*
             * رد سایر پیشنهادها
             */
            PurchaseQuote::query()
                ->where(
                    'purchase_request_id',
                    $request->id
                )
                ->whereKeyNot($purchaseQuote->id)
                ->update([
                    'status' => 'rejected',
                ]);

            /*
             * تأیید درخواست
             */
            $request->update([
                'status' => 'approved',
            ]);

            /*
             * اگر درخواست از تولید آمده باشد،
             * وضعیت ProductionRequirement نیز تغییر کند.
             */
            if ($request->productionRequirement) {

                $request->productionRequirement->update([
                    'status' => 'approved',
                ]);
            }

            /*
             * ایجاد سفارش خرید
             */
            return PurchaseOrder::createFromQuote(
                $purchaseQuote
            );
        });


        /*
         * اطلاع به تأمین‌کننده
         */
        $this->createSupplierPurchaseQuoteApproveLetter(
            $purchaseQuote,
            $sms
        );


        /*
         * اطلاع به واحد مالی
         */
        $this->createFinancePurchaseRequestLetter(
            $purchaseQuote,
            $purchaseOrder,
            $sms
        );


        return back()->with(
            'success',
            'پیشنهاد تأمین‌کننده تأیید و سفارش خرید ایجاد شد.'
        );
    }

    protected function createSupplierPurchaseQuoteApproveLetter(
        PurchaseQuote $purchaseQuote,
        NikSmsService $sms
    ): void {
        $purchaseQuote->load([
            'purchaseRequest.componentProduct',
            'purchaseRequest.productionRequirement.productionPlan.product',
            'supplier',
        ]);

        $request = $purchaseQuote->purchaseRequest;
        $supplier = $purchaseQuote->supplier;

        $componentName = $request->componentProduct?->title
            ?? 'قطعه';

        $requirement = $request->productionRequirement;

        $deviceName = $requirement?->productionPlan?->product?->title
            ?? 'درخواست خرید مستقیم';

        $quantity = $purchaseQuote->quantity;

        $unitPrice = $purchaseQuote->unit_price;

        $subject = "تایید اعلام قیمت قطعه - {$componentName}";

        $body = "
        <p>
            {$supplier->name} گرامی
        </p>

        <p>
            اعلام قیمت شما برای قطعه
            <strong>{$componentName}</strong>
            تأیید شد.
        </p>

        <p>
            <strong>محصول:</strong>
            {$deviceName}
        </p>

        <p>
            <strong>تعداد:</strong>
            {$quantity}
        </p>

        <p>
            <strong>قیمت واحد:</strong>
            " . number_format($unitPrice) . "
        </p>

        <p>
            سفارش خرید ایجاد شده است.
            لطفاً برای ثبت اطلاعات ارسال سفارش اقدام کنید.
        </p>
    ";

        $letter = Letter::create([
            'sender_id' => auth()->id(),
            'subject' => $subject,
            'body' => $body,
            'priority' => 'medium',
            'status' => 'sent',
        ]);

        // ارسال به تأمین‌کننده
        $letter->receivers()->attach($supplier->id, [
            'status' => 'new',
        ]);

        // SMS
        if (!empty($supplier->mobile)) {
            $message =
                "اعلام قیمت شما برای {$componentName} تأیید شد. "
                . "تعداد: {$quantity}. "
                . "لطفاً اطلاعات ارسال سفارش را ثبت کنید.";

            $sms->sendSingle(
                $supplier->mobile,
                $message
            );
        }
    }

    protected function createFinancePurchaseRequestLetter(
        PurchaseQuote $purchaseQuote,
        PurchaseOrder $purchaseOrder,
        NikSmsService $sms
    ): void {
        $purchaseQuote->load([
            'purchaseRequest.componentProduct',
            'purchaseRequest.productionRequirement.productionPlan.product',
            'supplier',
        ]);

        $request = $purchaseQuote->purchaseRequest;

        $componentName = $request->componentProduct?->title
            ?? 'قطعه';

        $requirement = $request->productionRequirement;

        $deviceName = $requirement?->productionPlan?->product?->title
            ?? 'درخواست خرید مستقیم';

        $supplierName = $purchaseQuote->supplier?->name
            ?? 'تأمین‌کننده';

        $quantity = $purchaseQuote->quantity;

        $unitPrice = $purchaseQuote->unit_price;

        $totalAmount = $purchaseOrder->total_amount;

        /*
        |--------------------------------------------------------------------------
        | کاربران واحد مالی
        |--------------------------------------------------------------------------
        */

        $financeUsers = User::query()
            ->whereHas('departments', function ($query) {
                $query->where('code', 'finance')
                    ->where('is_active', true);
            })
            ->get();

        if ($financeUsers->isEmpty()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ایجاد نامه
        |--------------------------------------------------------------------------
        */

        $subject =
            "پیگیری مالی سفارش خرید {$purchaseOrder->order_number}";

        $body = "
            <p>
                یک سفارش خرید جدید برای بررسی مالی ایجاد شده است.
            </p>
    
            <p>
                <strong>شماره سفارش:</strong>
                {$purchaseOrder->order_number}
            </p>
    
            <p>
                <strong>تأمین‌کننده:</strong>
                {$supplierName}
            </p>
    
            <p>
                <strong>قطعه:</strong>
                {$componentName}
            </p>
    
            <p>
                <strong>مربوط به:</strong>
                {$deviceName}
            </p>
    
            <p>
                <strong>تعداد:</strong>
                {$quantity}
            </p>
    
            <p>
                <strong>قیمت واحد:</strong>
                " . number_format($unitPrice) . "
            </p>
    
            <p>
                <strong>مبلغ کل سفارش:</strong>
                " . number_format($totalAmount) . "
            </p>
    
            <p>
                لطفاً سفارش را از نظر مالی بررسی و اقدامات لازم را انجام دهید.
            </p>
        ";

        $letter = Letter::create([
            'sender_id' => auth()->id(),
            'subject' => $subject,
            'body' => $body,
            'priority' => 'high',
            'status' => 'sent',
        ]);

        /*
        |--------------------------------------------------------------------------
        | گیرندگان نامه
        |--------------------------------------------------------------------------
        */

        foreach ($financeUsers as $financeUser) {

            $letter->receivers()->attach($financeUser->id, [
                'status' => 'new',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SMS
            |--------------------------------------------------------------------------
            */

            if (!empty($financeUser->mobile)) {

                $message =
                    "سفارش خرید {$purchaseOrder->order_number} "
                    . "برای بررسی مالی ایجاد شد. "
                    . "مبلغ: "
                    . number_format($totalAmount)
                    . " تومان";

                $sms->sendSingle(
                    $financeUser->mobile,
                    $message
                );
            }
        }
    }

}
