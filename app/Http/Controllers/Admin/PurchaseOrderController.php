<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\Sms\NikSmsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Morilog\Jalali\Jalalian;

class PurchaseOrderController extends Controller
{
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load([
            'supplier',
            'creator',
            'productionPlan.product',
            'items.componentProduct',
            'items.requirement',
            'receipts.receiver',
            'receipts.items.purchaseOrderItem',
        ]);

        return view(
            'admin.purchase-orders.show',
            compact('purchaseOrder')
        );
    }
    public function shipForm(
        PurchaseOrder $purchaseOrder
    ): View {
        $this->ensureSupplierOwnsOrder($purchaseOrder);

        if (!in_array(
            $purchaseOrder->status,
            ['pending', 'confirmed'],
            true
        )) {
            abort(
                422,
                'این سفارش در وضعیت قابل ارسال نیست.'
            );
        }

        $purchaseOrder->load([
            'items.componentProduct',
            'productionPlan.product',
        ]);

        return view(
            'admin.purchase-orders.ship',
            compact('purchaseOrder')
        );
    }

    public function ship(
        Request $request,
        PurchaseOrder $purchaseOrder,
        NikSmsService $sms
    ): RedirectResponse {
        $this->ensureSupplierOwnsOrder($purchaseOrder);

        if (!in_array(
            $purchaseOrder->status,
            ['pending', 'confirmed'],
            true
        )) {
            throw ValidationException::withMessages([
                'purchaseOrder' =>
                    'این سفارش در وضعیت قابل ارسال نیست.',
            ]);
        }

        $validated = $request->validate([
            'shipped_at' => [
                'required',
                'string',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'vehicle_plate' => [
                'nullable',
                'string',
                'max:50',
            ],

            'driver_name' => [
                'required',
                'string',
                'max:150',
            ],

            'driver_mobile' => [
                'nullable',
                'string',
                'max:30',
            ],

            'shipping_note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $quote = $purchaseOrder->quote;

        $shippedAt = Jalalian::fromFormat(
            'Y/m/d',
            $validated['shipped_at']
        )->toCarbon()->format('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | ثبت اطلاعات ارسال
        |--------------------------------------------------------------------------
        */

        $purchaseOrder->update([
            'shipped_at' => $shippedAt,
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_plate' => $validated['vehicle_plate'] ?? null,
            'driver_name' => $validated['driver_name'],
            'driver_mobile' => $validated['driver_mobile'] ?? null,
            'shipping_note' => $validated['shipping_note'] ?? null,
            'status' => 'sent',
        ]);

        $quote->update([
            'status' => 'sent',
        ]);

        /*
        |--------------------------------------------------------------------------
        | اطلاع به واحد انبار
        |--------------------------------------------------------------------------
        */

        $this->notifyWarehouseAboutShipment(
            $purchaseOrder,
            $sms
        );

        return redirect()
            ->route(
                'supplier.purchase-requests.index'
            )
            ->with(
                'success',
                'اطلاعات ارسال سفارش با موفقیت ثبت شد و واحد انبار مطلع گردید.'
            );
    }

    private function ensureSupplierOwnsOrder(
        PurchaseOrder $purchaseOrder
    ): void {
        if (
            (int) $purchaseOrder->supplier_id !==
            (int) auth()->id()
        ) {
            abort(403);
        }
    }

    protected function notifyWarehouseAboutShipment(
        PurchaseOrder $purchaseOrder,
        NikSmsService $sms
    ): void {
        $purchaseOrder->load([
            'supplier',
            'quote.purchaseRequest.componentProduct',
            'quote.purchaseRequest.productionRequirement.productionPlan.product',
            'items.componentProduct',
        ]);

        /*
        |--------------------------------------------------------------------------
        | پیدا کردن کاربران واحد انبار
        |--------------------------------------------------------------------------
        */

        $warehouseUsers = User::query()
            ->whereHas('departments', function ($query) {
                $query->where('code', 'warehouse')
                    ->where('is_active', true);
            })
            ->get();

        if ($warehouseUsers->isEmpty()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | اطلاعات سفارش
        |--------------------------------------------------------------------------
        */

        $supplierName = $purchaseOrder->supplier?->name
            ?? 'تأمین‌کننده';

        $orderNumber = $purchaseOrder->order_number;

        $shippedAt = $purchaseOrder->shipped_at
            ? Jalalian::fromCarbon(
                $purchaseOrder->shipped_at
            )->format('Y/m/d')
            : '-';

        $driverName = $purchaseOrder->driver_name
            ?: '-';

        $driverMobile = $purchaseOrder->driver_mobile
            ?: '-';

        $vehicleType = $purchaseOrder->vehicle_type
            ?: '-';

        $vehiclePlate = $purchaseOrder->vehicle_plate
            ?: '-';

        $shippingNote = $purchaseOrder->shipping_note
            ?: '-';

        /*
        |--------------------------------------------------------------------------
        | لیست اقلام سفارش
        |--------------------------------------------------------------------------
        */

        $itemsHtml = '';

        foreach ($purchaseOrder->items as $item) {

            $productName = $item->componentProduct?->title
                ?? 'قطعه';

            $itemsHtml .= "
            <li>
                <strong>{$productName}</strong>
                - تعداد: {$item->quantity}
                {$item->unit}
            </li>
        ";
        }

        /*
        |--------------------------------------------------------------------------
        | متن نامه
        |--------------------------------------------------------------------------
        */

        $subject =
            "اطلاعیه ارسال بار - سفارش {$orderNumber}";

        $body = "
        <p>
            واحد محترم انبار
        </p>

        <p>
            سفارش خرید زیر توسط تأمین‌کننده ارسال شده است.
            لطفاً هماهنگی‌های لازم برای دریافت بار را انجام دهید.
        </p>

        <p>
            <strong>شماره سفارش:</strong>
            {$orderNumber}
        </p>

        <p>
            <strong>تأمین‌کننده:</strong>
            {$supplierName}
        </p>

        <p>
            <strong>تاریخ ارسال:</strong>
            {$shippedAt}
        </p>

        <p>
            <strong>راننده:</strong>
            {$driverName}
        </p>

        <p>
            <strong>شماره تماس راننده:</strong>
            {$driverMobile}
        </p>

        <p>
            <strong>نوع خودرو:</strong>
            {$vehicleType}
        </p>

        <p>
            <strong>پلاک خودرو:</strong>
            {$vehiclePlate}
        </p>

        <p>
            <strong>اقلام سفارش:</strong>
        </p>

        <ul>
            {$itemsHtml}
        </ul>

        <p>
            <strong>توضیحات ارسال:</strong>
            {$shippingNote}
        </p>

        <p>
            لطفاً پس از دریافت بار، فرآیند ثبت رسید انبار را انجام دهید.
        </p>
    ";

        /*
        |--------------------------------------------------------------------------
        | ایجاد نامه
        |--------------------------------------------------------------------------
        */

        $letter = Letter::create([
            'sender_id' => auth()->id(),
            'subject' => $subject,
            'body' => $body,
            'priority' => 'high',
            'status' => 'sent',

            // اگر department_id در جدول letters دارید:
            // 'department_id' => $warehouseUsers->first()->departments
            //     ->where('code', 'warehouse')
            //     ->first()?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ارسال نامه به اعضای واحد انبار + SMS
        |--------------------------------------------------------------------------
        */

        foreach ($warehouseUsers as $warehouseUser) {

            $letter->receivers()->attach($warehouseUser->id, [
                'status' => 'new',
            ]);

            if (!empty($warehouseUser->mobile)) {

                $message =
                    "بار سفارش {$orderNumber} توسط "
                    . "{$supplierName} ارسال شد. "
                    . "تاریخ ارسال: {$shippedAt}. "
                    . "راننده: {$driverName}. "
                    . "لطفاً برای دریافت بار هماهنگ کنید.";

                $sms->send(
                    $warehouseUser->mobile,
                    $message
                );
            }
        }
    }
}