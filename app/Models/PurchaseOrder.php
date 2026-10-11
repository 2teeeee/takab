<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'order_number',
        'purchase_quote_id',
        'supplier_id',
        'production_plan_id',
        'status',
        'order_date',
        'expected_delivery_date',
        'total_amount',
        'note',
        'created_by',

        'shipped_at',
        'vehicle_type',
        'vehicle_plate',
        'driver_name',
        'driver_mobile',
        'shipping_note',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'shipped_at' => 'date',
        'total_amount' => 'integer',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(
            PurchaseQuote::class,
            'purchase_quote_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'supplier_id'
        );
    }

    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(
            ProductionPlan::class
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            PurchaseOrderItem::class
        );
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(
            PurchaseReceipt::class
        );
    }

    public static function createFromQuote(
        PurchaseQuote $quote
    ): self {
        $request = $quote->purchaseRequest;

        if (!$request) {
            throw new \RuntimeException(
                'درخواست خرید مربوط به اعلام قیمت پیدا نشد.'
            );
        }

        $requirement = $request->productionRequirement;

        /*
        |--------------------------------------------------------------------------
        | تعیین محصول و مقدار سفارش
        |--------------------------------------------------------------------------
        */

        if ($requirement) {
            // درخواست وابسته به برنامه تولید
            $componentProductId = $requirement->component_product_id;

            $requiredQuantity = (float) $requirement->purchase_quantity;

            $unit = $requirement->unit;
            $productionPlanId = $requirement->production_plan_id;

            $requirementId = $requirement->id;
        } else {
            // درخواست خرید مستقیم
            $componentProductId = $request->component_product_id;

            $requiredQuantity = (float) $request->quantity;

            $unit = $request->unit;
            $productionPlanId = null;

            $requirementId = null;
        }

        if (!$componentProductId) {
            throw new \RuntimeException(
                'محصول درخواست خرید مشخص نشده است.'
            );
        }

        if ($requiredQuantity <= 0) {
            throw new \RuntimeException(
                'مقدار موردنیاز برای سفارش باید بیشتر از صفر باشد.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | کنترل مقدار قابل تأمین
        |--------------------------------------------------------------------------
        */

        $availableQuantity = (float) $quote->available_quantity;

        if ($availableQuantity <= 0) {
            throw new \RuntimeException(
                'مقدار قابل تأمین اعلام‌شده توسط تأمین‌کننده معتبر نیست.'
            );
        }

        $quantity = min(
            $availableQuantity,
            $requiredQuantity
        );

        $unitPrice = (int) $quote->unit_price;

        /*
        |--------------------------------------------------------------------------
        | ایجاد سفارش خرید
        |--------------------------------------------------------------------------
        */

        $order = self::create([
            'order_number' => self::generateOrderNumber(),
            'purchase_quote_id' => $quote->id,
            'supplier_id' => $quote->supplier_id,
            'production_plan_id' => $productionPlanId,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'expected_delivery_date' => now()
                ->addDays($quote->delivery_days ?? 0)
                ->toDateString(),
            'total_amount' => (int) round(
                $quantity * $unitPrice
            ),
            'created_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ثبت قلم سفارش
        |--------------------------------------------------------------------------
        */

        $order->items()->create([
            'production_requirement_id' => $requirementId,
            'component_product_id' => $componentProductId,
            'quantity' => $quantity,
            'received_quantity' => 0,
            'unit_price' => $unitPrice,
            'total_price' => (int) round(
                $quantity * $unitPrice
            ),
            'unit' => $unit,
            'note' => $quote->note,
        ]);

        return $order;
    }

    private static function generateOrderNumber(): string
    {
        do {
            $number = 'PO-' . now()->format('YmdHis');

            $number .= '-' . random_int(100, 999);

        } while (
            self::where('order_number', $number)->exists()
        );

        return $number;
    }
}
