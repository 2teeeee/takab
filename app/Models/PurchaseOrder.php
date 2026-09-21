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
        $requirement = $request->productionRequirement;

        $quantity = min(
            (float) $quote->available_quantity,
            (float) $requirement->quantity
        );

        $unitPrice = (int) $quote->unit_price;

        $order = self::create([
            'order_number' => self::generateOrderNumber(),
            'purchase_quote_id' => $quote->id,
            'supplier_id' => $quote->supplier_id,
            'production_plan_id' =>
                $requirement->production_plan_id,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'expected_delivery_date' =>
                now()
                    ->addDays($quote->delivery_days ?? 0)
                    ->toDateString(),
            'total_amount' =>
                (int) round($quantity * $unitPrice),
            'created_by' => auth()->id(),
        ]);

        $order->items()->create([
            'production_requirement_id' =>
                $requirement->id,

            'component_product_id' =>
                $requirement->component_product_id,

            'quantity' => $quantity,

            'received_quantity' => 0,

            'unit_price' => $unitPrice,

            'total_price' =>
                (int) round($quantity * $unitPrice),

            'unit' => $requirement->unit,

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
