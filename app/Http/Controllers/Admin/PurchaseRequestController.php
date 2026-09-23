<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\Product;
use App\Models\ProductionRequirement;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            (float) $productionRequirement->purchase_quantity <= 0,
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

    public function store(
        Request $request,
        NikSmsService $sms
    ) {
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

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
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
                        'برای این قطعه نیازی به خرید وجود ندارد.',
                ])
                ->withInput();
        }

        $supplierIds = collect(
            $validated['supplier_ids']
        )->unique()->values();

        /*
         * فقط کاربران دارای نقش supplier
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
                        'یکی از تأمین‌کنندگان انتخاب‌شده معتبر نیست.',
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
                 * جلوگیری از ایجاد درخواست تکراری
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

                    'component_product_id' =>
                        $requirement->component_product_id,

                    'supplier_id' =>
                        $supplierId,

                    'quantity' =>
                        $validated['quantity'],

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

            if ($requests->isNotEmpty()) {
                $requirement->update([
                    'status' => 'coordinating',
                ]);
            }

            return $requests;
        });

        /*
         * ایجاد نامه و ارسال SMS
         */
        foreach ($purchaseRequests as $purchaseRequest) {
            $this->createSupplierPurchaseRequestLetter(
                $purchaseRequest,
                $sms
            );
        }

        return redirect()
            ->route(
                'admin.production-plans.show',
                $requirement->production_plan_id
            )
            ->with(
                'success',
                $purchaseRequests->count()
                . ' درخواست قیمت ایجاد و برای تأمین‌کنندگان ارسال شد.'
            );
    }

    public function createNoOrder(): View
    {
        $suppliers = User::query()
            ->role('supplier')
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'admin.purchase-requests.create-no-order',
            compact(
                'suppliers',
                'products'
            )
        );
    }

    public function storeNoOrder(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            'supplier_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'component_product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'deadline' => [
                'required',
                'date',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        /*
         * اطمینان از اینکه کاربر انتخاب‌شده واقعاً Supplier است.
         */
        $supplier = User::query()
            ->role('supplier')
            ->findOrFail(
                $validated['supplier_id']
            );

        /*
         * محصول.
         */
        $product = Product::query()
            ->findOrFail(
                $validated['component_product_id']
            );

        $deadline = $validated['deadline'] ? Jalalian::fromFormat(
            'Y/m/d',
            $validated['deadline']
        )->toCarbon()->format('Y-m-d') : null;

        /*
         * ایجاد PurchaseRequest مستقیم.
         *
         * production_requirement_id عمداً NULL است.
         */
        $purchaseRequest = PurchaseRequest::create([

            'production_requirement_id' => null,

            'component_product_id' =>
                $product->id,

            'supplier_id' =>
                $supplier->id,

            'quantity' =>
                $validated['quantity'],

            'unit' =>
                $validated['unit'] ?? null,

            'status' =>
                'sent',

            'requested_at' =>
                now(),

            'deadline' =>
                $deadline,

            'note' =>
                $validated['note'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.purchase-requests.show',
                $purchaseRequest
            )
            ->with(
                'success',
                'درخواست تأمین با موفقیت ایجاد و برای تأمین‌کننده ارسال شد.'
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

    /**
     * ایجاد نامه و ارسال پیامک به تأمین‌کننده
     */
    protected function createSupplierPurchaseRequestLetter(
        PurchaseRequest $purchaseRequest,
        NikSmsService $sms
    ): void {
        $purchaseRequest->loadMissing([
            'supplier',
            'productionRequirement.componentProduct',
            'productionRequirement.productionPlan.product',
        ]);

        $supplier = $purchaseRequest->supplier;

        if (!$supplier) {
            return;
        }

        $requirement = $purchaseRequest->productionRequirement;

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
                    (float) $purchaseRequest->quantity,
                    4,
                    '.',
                    ','
                ),
                '0'
            ),
            '.'
        );

        $deadline = $purchaseRequest->deadline
            ? $purchaseRequest->deadline->format('Y/m/d')
            : 'بدون مهلت مشخص';

        $actor = Auth::user();

        $message = <<<TEXT
تأمین‌کننده محترم {$supplierName}

برای تأمین قطعه مورد نیاز برنامه تولید، درخواست اعلام قیمت برای شما ثبت شده است.

دستگاه تولیدی:
{$productName}

قطعه / ماده:
{$componentName}

مقدار مورد نیاز:
{$quantity} {$purchaseRequest->unit}

مهلت اعلام قیمت:
{$deadline}

{$purchaseRequest->note}

لطفاً قیمت پیشنهادی، مقدار قابل تأمین و زمان تحویل را از طریق پنل تأمین‌کنندگان ثبت فرمایید.

با تشکر
واحد تأمین
TEXT;

        $letter = Letter::create([
            'sender_id' => $actor->id,

            'subject' =>
                "درخواست اعلام قیمت قطعه - {$componentName}",

            'body' => $message,

            'priority' => 'medium',
        ]);

        $letter->receiverItems()->create([
            'user_id' => $supplier->id,
            'status' => 'new',
            'last_received_at' => now(),
        ]);

        $smsMessage = <<<TEXT
یک درخواست قیمت جدید برای شما ثبت شده است.

موضوع:
{$letter->subject}

لطفاً وارد پنل تأمین‌کنندگان شوید و قیمت پیشنهادی خود را ثبت نمایید.

{$letter->url}
TEXT;

        $sms->sendSingle(
            $supplier->mobile,
            $smsMessage
        );

        $purchaseRequest->update([
            'status' => 'sent',
        ]);
    }
}