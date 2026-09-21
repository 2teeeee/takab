<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductUser;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReceipt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;

class PurchaseReceiptController extends Controller
{
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with([
                'supplier',
                'productionPlan.product.translation',
                'items.componentProduct.translation',
            ])
            ->whereIn('status', [
                'sent',
                'partially_received',
            ])
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.purchase-receipts.index',
            compact('purchaseOrders')
        );
    }

    public function create(
        PurchaseOrder $purchaseOrder
    ): View {
        if (!in_array(
            $purchaseOrder->status,
            ['sent', 'partially_received'],
            true
        )) {
            abort(
                422,
                'این سفارش در وضعیت قابل دریافت نیست.'
            );
        }

        $purchaseOrder->load([
            'supplier',
            'productionPlan.product.translation',
            'items.componentProduct.translation',
            'receipts.receiver',
            'receipts.items.purchaseOrderItem',
        ]);

        return view(
            'admin.purchase-receipts.create',
            compact('purchaseOrder')
        );
    }

    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {
        $validated = $request->validate([
            'receipt_date' => [
                'required',
                'date',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.purchase_order_item_id' => [
                'required',
                'integer',
                'exists:purchase_order_items,id',
            ],

            'items.*.quantity' => [
                'nullable',
                'numeric',
                'gt:0',
            ],

            'items.*.note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $companyUserId = config('shop.company_user_id');

        if (!$companyUserId) {
            throw ValidationException::withMessages([
                'items' => 'انبار اصلی در تنظیمات سیستم مشخص نشده است.',
            ]);
        }

        DB::transaction(function () use (
            $purchaseOrder,
            $validated,
            $companyUserId
        ) {
            /*
             * قفل سفارش برای جلوگیری از ثبت همزمان
             * دو رسید برای یک سفارش.
             */
            $order = PurchaseOrder::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($purchaseOrder->id);

            if (!in_array(
                $order->status,
                ['sent', 'partially_received'],
                true
            )) {
                throw ValidationException::withMessages([
                    'items' => 'این سفارش دیگر قابل دریافت نیست.',
                ]);
            }

            $orderItems = $order->items->keyBy('id');

            /*
             * فقط آیتم‌هایی که واقعاً متعلق به همین سفارش هستند.
             */
            $receiptItems = [];

            foreach ($validated['items'] as $item) {

                $quantity = (float) ($item['quantity'] ?? 0);

                if ($quantity <= 0) {
                    continue;
                }

                $orderItemId = (int) $item[
                'purchase_order_item_id'
                ];

                $orderItem = $orderItems->get($orderItemId);

                if (!$orderItem) {
                    throw ValidationException::withMessages([
                        'items' => 'یکی از اقلام متعلق به این سفارش نیست.',
                    ]);
                }

                $orderedQuantity = (float) $orderItem->quantity;
                $receivedQuantity = (float) $orderItem->received_quantity;

                $remainingQuantity =
                    $orderedQuantity - $receivedQuantity;

                if ($quantity > $remainingQuantity) {
                    throw ValidationException::withMessages([
                        'items' =>
                            "مقدار دریافت برای {$orderItem->componentProduct?->title} "
                            . "نمی‌تواند بیشتر از مقدار باقی‌مانده باشد.",
                    ]);
                }

                $receiptItems[] = [
                    'order_item' => $orderItem,
                    'quantity' => $quantity,
                    'note' => $item['note'] ?? null,
                ];
            }

            if (!$receiptItems) {
                throw ValidationException::withMessages([
                    'items' => 'حداقل یک مقدار برای دریافت وارد کنید.',
                ]);
            }

            /*
             * ایجاد رسید.
             */

            $receiptDate = Jalalian::fromFormat(
                'Y/m/d',
                $validated['receipt_date']
            )->toCarbon()->format('Y-m-d');

            $receipt = PurchaseReceipt::create([
                'receipt_number' => $this->generateReceiptNumber(),
                'purchase_order_id' => $order->id,
                'received_by' => auth()->id(),
                'receipt_date' => $receiptDate,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($receiptItems as $receiptItem) {

                $orderItem = $receiptItem['order_item'];
                $quantity = $receiptItem['quantity'];

                /*
                 * ثبت آیتم رسید.
                 */
                $receipt->items()->create([
                    'purchase_order_item_id' => $orderItem->id,
                    'quantity' => $quantity,
                    'note' => $receiptItem['note'],
                ]);

                /*
                 * افزایش مقدار دریافتی سفارش.
                 */
                $orderItem->increment(
                    'received_quantity',
                    $quantity
                );

                /*
                 * افزایش موجودی انبار اصلی.
                 */
                ProductUser::increase(
                    $companyUserId,
                    $orderItem->component_product_id,
                    $quantity
                );
            }

            /*
             * تعیین وضعیت جدید سفارش.
             */
            $order->refresh();
            $order->load('items');

            $allReceived = $order->items->every(
                fn ($item) =>
                    (float) $item->received_quantity >=
                    (float) $item->quantity
            );

            $hasReceived = $order->items->contains(
                fn ($item) =>
                    (float) $item->received_quantity > 0
            );

            if ($allReceived) {

                $order->update([
                    'status' => 'received',
                ]);

            } elseif ($hasReceived) {

                $order->update([
                    'status' => 'partially_received',
                ]);
            }
        });

        return redirect()
            ->route(
                'admin.purchase-receipts.index'
            )
            ->with(
                'success',
                'دریافت کالا با موفقیت ثبت و موجودی انبار به‌روزرسانی شد.'
            );
    }

    private function generateReceiptNumber(): string
    {
        do {
            $number =
                'GRN-' .
                now()->format('YmdHis') .
                '-' .
                random_int(100, 999);

        } while (
            PurchaseReceipt::query()
                ->where('receipt_number', $number)
                ->exists()
        );

        return $number;
    }
}
