<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
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
        PurchaseOrder $purchaseOrder
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

        return redirect()
            ->route(
                'supplier.purchase-requests.index'
            )
            ->with(
                'success',
                'اطلاعات ارسال سفارش با موفقیت ثبت شد.'
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
}