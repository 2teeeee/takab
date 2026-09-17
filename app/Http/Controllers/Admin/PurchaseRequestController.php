<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionRequirement;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;

class PurchaseRequestController extends Controller
{
    public function index(): View
    {
        $purchaseRequests = PurchaseRequest::query()
            ->with([
                'productionRequirement.componentProduct',
                'productionRequirement.productionPlan.product',
                'supplier',
            ])
            ->withCount('quotes')
            ->latest()
            ->paginate(20);

        return view(
            'admin.purchase-requests.index',
            compact('purchaseRequests')
        );
    }

    public function create(
        ProductionRequirement $productionRequirement
    ): View {

        abort_if(
            $productionRequirement->purchase_quantity <= 0,
            422,
            'برای این قطعه نیازی به خرید وجود ندارد.'
        );

        $productionRequirement->load([
            'componentProduct',
            'productionPlan.product',
            'productBom',
        ]);

        $suppliers = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->orderBy('name')
            ->get();

        return view(
            'admin.purchase-requests.create',
            compact(
                'productionRequirement',
                'suppliers'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_requirement_id' => [
                'required',
                'exists:production_requirements,id',
            ],

            'supplier_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'supplier_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:users,id',
            ],

            'deadline' => [
                'nullable',
                'string',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $requirement = ProductionRequirement::query()
            ->with([
                'componentProduct',
                'productionPlan.product',
            ])
            ->findOrFail(
                $validated['production_requirement_id']
            );

        if ((float) $requirement->purchase_quantity <= 0) {
            return back()
                ->withErrors([
                    'supplier_ids' =>
                        'برای این قطعه نیازی به خرید وجود ندارد.'
                ])
                ->withInput();
        }

        $supplierIds = collect(
            $validated['supplier_ids']
        )->unique()->values();

        /*
         * فقط کاربرانی که نقش supplier دارند
         * اجازه دریافت درخواست قیمت دارند.
         */
        $validSupplierIds = User::query()
            ->whereIn('id', $supplierIds)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->pluck('id');

        if ($validSupplierIds->count() !== $supplierIds->count()) {
            return back()
                ->withErrors([
                    'supplier_ids' =>
                        'یکی از تأمین‌کنندگان انتخاب‌شده معتبر نیست.'
                ])
                ->withInput();
        }

        $purchaseRequests = DB::transaction(function () use (
            $validSupplierIds,
            $requirement,
            $validated
        ) {

            $requests = collect();

            foreach ($validSupplierIds as $supplierId) {

                /*
                 * جلوگیری از درخواست تکراری
                 */
                $existing = PurchaseRequest::query()
                    ->where(
                        'production_requirement_id',
                        $requirement->id
                    )
                    ->where(
                        'supplier_id',
                        $supplierId
                    )
                    ->whereIn('status', [
                        'pending',
                        'sent',
                        'viewed',
                    ])
                    ->exists();

                if ($existing) {
                    continue;
                }

                $deadline = $validated['deadline'] ? Jalalian::fromFormat(
                    'Y/m/d',
                    $validated['deadline']
                )->toCarbon()->format('Y-m-d') : null;

                $purchaseRequest = PurchaseRequest::create([
                    'production_requirement_id' =>
                        $requirement->id,

                    'supplier_id' =>
                        $supplierId,

                    'quantity' =>
                        $requirement->purchase_quantity,

                    'unit' =>
                        $requirement->unit,

                    'status' =>
                        'pending',

                    'requested_at' =>
                        now(),

                    'deadline' =>
                        $deadline,

                    'note' =>
                        $validated['note'] ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);

                $requests->push(
                    $purchaseRequest
                );
            }

            /*
             * اگر حداقل یک درخواست ایجاد شد،
             * وضعیت نیازمندی را coordinating می‌کنیم.
             */
            if ($requests->isNotEmpty()) {
                $requirement->update([
                    'status' => 'coordinating',
                ]);
            }

            return $requests;
        });

        /*
         * در مرحله بعد:
         *
         * 1. ایجاد نامه
         * 2. ایجاد receiver
         * 3. ارسال SMS
         *
         * برای هر PurchaseRequest
         */

        foreach ($purchaseRequests as $purchaseRequest) {

            // TODO:
            // $this->sendSupplierNotification($purchaseRequest);
        }

        return redirect()
            ->route(
                'admin.production-plans.show',
                $requirement->production_plan_id
            )
            ->with(
                'success',
                $purchaseRequests->count()
                . ' درخواست قیمت ایجاد شد.'
            );
    }

    public function show(
        PurchaseRequest $purchaseRequest
    ): View {

        $purchaseRequest->load([
            'supplier',
            'productionRequirement.componentProduct',
            'productionRequirement.productionPlan.product',
            'productionRequirement.productBom',
            'quotes',
        ]);

        return view(
            'admin.purchase-requests.show',
            compact('purchaseRequest')
        );
    }
}
