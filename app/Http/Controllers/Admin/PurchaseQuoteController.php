<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\PurchaseOrder;
use App\Models\PurchaseQuote;
use App\Models\PurchaseRequest;
use App\Services\Sms\NikSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseQuoteController extends Controller
{
    public function approve(
        PurchaseQuote $purchaseQuote,
        NikSmsService $sms
    ): RedirectResponse {
        DB::transaction(function () use ($purchaseQuote) {

            $purchaseQuote->load([
                'purchaseRequest.productionRequirement',
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

            $requirement =
                $request->productionRequirement;

            if ($requirement->purchase_quantity <= 0) {
                abort(
                    422,
                    'برای این قطعه نیازی به خرید وجود ندارد.'
                );
            }

            $purchaseQuote->update([
                'status' => 'approved',
            ]);

            // رد کردن سایر پیشنهادها
            PurchaseQuote::query()
                ->where('purchase_request_id', $request->id)
                ->whereKeyNot($purchaseQuote->id)
                ->update([
                    'status' => 'rejected',
                ]);

            $request->update([
                'status' => 'approved',
            ]);

            $requirement->update([
                'status' => 'approved',
            ]);

            PurchaseOrder::createFromQuote(
                $purchaseQuote
            );
        });

        $this->createSupplierPurchaseQuoteApproveLetter(
            $purchaseQuote,
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
        $purchaseQuote->loadMissing([
            'supplier',
            'purchaseRequest',
            'purchaseRequest.productionRequirement.componentProduct',
            'purchaseRequest.productionRequirement.productionPlan.product',
        ]);

        $supplier = $purchaseQuote->supplier;

        if (!$supplier) {
            return;
        }

        $requirement = $purchaseQuote->purchaseRequest->productionRequirement;

        $supplierName = $supplier->name ?? 'تأمین‌کننده';

        $productName =
            $requirement
                ->productionPlan
                ->product
                ?->translation
                ?->title ?? '—';

        $componentName =
            $requirement
                ->componentProduct
                ?->translation
                ?->title ?? '—';

        $quantity = rtrim(
            rtrim(
                number_format(
                    (float) $purchaseQuote->available_quantity,
                    4,
                    '.',
                    ','
                ),
                '0'
            ),
            '.'
        );

        $actor = Auth::user();

        $message = <<<TEXT
تأمین‌کننده محترم {$supplierName}

برای تأمین قطعه مورد نیاز برنامه تولید، قیمت شما مورد تایید قرار گرفته است.


قطعه / ماده:
{$componentName}

مقدار مورد نیاز:
{$quantity} {$purchaseQuote->purchaseRequest->unit}

لطفا نسبت با تامین طبق زمان مشخص شده اقدام نمایید.

با تشکر
واحد تأمین
TEXT;

        $letter = Letter::create([
            'sender_id' => $actor->id,

            'subject' =>
                "تایید اعلام قیمت قطعه - {$componentName}",

            'body' => $message,

            'priority' => 'medium',
        ]);

        $letter->receiverItems()->create([
            'user_id' => $supplier->id,
            'status' => 'new',
            'last_received_at' => now(),
        ]);

        $smsMessage = <<<TEXT
قیمت شما مورد تایید قرار گرفته است.

موضوع:
{$letter->subject}

لطفاً نسبت با تامین قطعه مورد نظر اقدام و پس از تامین در پنل نسبت به اطلاع رسانی آن اقدام نمایید.

{$letter->url}
TEXT;

        $sms->sendSingle(
            $supplier->mobile,
            $smsMessage
        );

    }


}