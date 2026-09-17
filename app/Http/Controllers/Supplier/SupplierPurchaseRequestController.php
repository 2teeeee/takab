<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplierPurchaseRequestController extends Controller
{
    public function index(): View
    {
        $purchaseRequests = PurchaseRequest::query()
            ->where(
                'supplier_id',
                auth()->id()
            )
            ->with([
                'productionRequirement.componentProduct',
                'productionRequirement.productionPlan.product',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'supplier.purchase-requests.index',
            compact('purchaseRequests')
        );
    }

    public function show(
        PurchaseRequest $purchaseRequest
    ): View {

        abort_unless(
            $purchaseRequest->supplier_id === auth()->id(),
            403
        );

        $purchaseRequest->load([
            'productionRequirement.componentProduct',
            'productionRequirement.productionPlan.product',
            'quotes',
        ]);

        if ($purchaseRequest->status === 'sent') {
            $purchaseRequest->update([
                'status' => 'viewed',
            ]);
        }

        return view(
            'supplier.purchase-requests.show',
            compact('purchaseRequest')
        );
    }

    public function quote(
        Request $request,
        PurchaseRequest $purchaseRequest
    ) {

        abort_unless(
            $purchaseRequest->supplier_id === auth()->id(),
            403
        );

        if (in_array($purchaseRequest->status, [
            'expired',
            'cancelled',
        ])) {
            return back()->withErrors([
                'quote' =>
                    'این درخواست دیگر قابل پاسخ نیست.'
            ]);
        }

        $validated = $request->validate([
            'available_quantity' => [
                'required',
                'numeric',
                'gt:0',
                'lte:' . $purchaseRequest->quantity,
            ],

            'unit_price' => [
                'required',
                'integer',
                'min:1',
            ],

            'delivery_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use (
            $purchaseRequest,
            $validated
        ) {

            $totalPrice =
                (float) $validated['available_quantity']
                * (int) $validated['unit_price'];

            $purchaseRequest->quotes()->create([

                'supplier_id' =>
                    auth()->id(),

                'available_quantity' =>
                    $validated['available_quantity'],

                'unit_price' =>
                    $validated['unit_price'],

                'total_price' =>
                    (int) round($totalPrice),

                'delivery_days' =>
                    $validated['delivery_days'] ?? null,

                'note' =>
                    $validated['note'] ?? null,

                'quoted_at' =>
                    now(),

            ]);

            $purchaseRequest->update([
                'status' => 'quoted',
            ]);

            /*
             * فعلاً وضعیت
             * ProductionRequirement
             * را تغییر نمی‌دهیم.
             *
             * در فاز ۲:
             * quoted
             */
        });

        return redirect()
            ->route(
                'supplier.purchase-requests.show',
                $purchaseRequest
            )
            ->with(
                'success',
                'قیمت پیشنهادی شما با موفقیت ثبت شد.'
            );
    }
}
