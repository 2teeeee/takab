<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBom;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductBomController extends Controller
{
    /**
     * لیست فرمول‌های ساخت
     */
    public function index(): View
    {
        $boms = ProductBom::query()
            ->withCount('suppliers')
            ->with([
                'product',
                'componentProduct',
            ])
            ->latest()
            ->paginate(20);

        return view('admin.product-boms.index', compact('boms'));
    }

    /**
     * فرم ایجاد BOM
     */
    public function create(): View
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->where('products.status', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })
            ->orderBy('t.title')
            ->select([
                'products.id',
                't.title',
            ])->get();

        $suppliers = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->orderBy('name')
            ->get();

        return view('admin.product-boms.create', compact(
            'products',
            'suppliers'
        ));
    }

    /**
     * ذخیره BOM
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'components' => [
                'required',
                'array',
                'min:1',
            ],

            'components.*.component_product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'components.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'components.*.unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'components.*.unit_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'components.*.note' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'components.*.suppliers' => [
                'nullable',
                'array',
            ],

            'components.*.suppliers.*.supplier_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'components.*.suppliers.*.unit_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'components.*.suppliers.*.is_default' => [
                'nullable',
                'boolean',
            ],

            'components.*.suppliers.*.is_active' => [
                'nullable',
                'boolean',
            ],

            'components.*.suppliers.*.note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->validateBomData($validated);

        DB::transaction(function () use ($validated) {

            foreach ($validated['components'] as $component) {

                $bom = ProductBom::create([
                    'product_id' => $validated['product_id'],
                    'component_product_id' => $component['component_product_id'],
                    'quantity' => $component['quantity'],
                    'unit' => $component['unit'] ?? null,
                    'unit_price' => $component['unit_price'],
                    'is_active' => true,
                    'note' => $component['note'] ?? null,
                ]);

                $this->syncSuppliers(
                    $bom,
                    $component['suppliers'] ?? []
                );
            }
        });

        return redirect()
            ->route('admin.product-boms.index')
            ->with('success', 'فرمول ساخت با موفقیت ایجاد شد.');
    }

    /**
     * نمایش BOM
     */
    public function show(ProductBom $productBom): View
    {
        $productBom->load([
            'product',
            'componentProduct',
            'suppliers.supplier',
        ]);

        return view('admin.product-boms.show', compact('productBom'));
    }

    /**
     * فرم ویرایش BOM
     */
    public function edit(ProductBom $productBom): View
    {
        $productBom->load([
            'product',
            'componentProduct',
            'suppliers.supplier',
        ]);

        $products = Product::query()
            ->orderBy('title')
            ->get();

        $suppliers = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->orderBy('name')
            ->get();

        return view('admin.product-boms.edit', compact(
            'productBom',
            'products',
            'suppliers'
        ));
    }

    /**
     * بروزرسانی BOM
     */
    public function update(
        Request $request,
        ProductBom $productBom
    ): RedirectResponse {

        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
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

            'unit_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'suppliers' => [
                'nullable',
                'array',
            ],

            'suppliers.*.supplier_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'suppliers.*.unit_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'suppliers.*.is_default' => [
                'nullable',
                'boolean',
            ],

            'suppliers.*.is_active' => [
                'nullable',
                'boolean',
            ],

            'suppliers.*.note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->validateBomData([
            'product_id' => $validated['product_id'],
            'components' => [
                [
                    'component_product_id' =>
                        $validated['component_product_id'],
                    'suppliers' =>
                        $validated['suppliers'] ?? [],
                ],
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $productBom
        ) {

            $productBom->update([
                'product_id' => $validated['product_id'],
                'component_product_id' =>
                    $validated['component_product_id'],
                'quantity' => $validated['quantity'],
                'unit' => $validated['unit'] ?? null,
                'unit_price' => $validated['unit_price'],
                'is_active' =>
                    $validated['is_active'] ?? false,
                'note' => $validated['note'] ?? null,
            ]);

            $this->syncSuppliers(
                $productBom,
                $validated['suppliers'] ?? []
            );
        });

        return redirect()
            ->route('admin.product-boms.index')
            ->with('success', 'فرمول ساخت با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف BOM
     */
    public function destroy(ProductBom $productBom): RedirectResponse
    {
        $productBom->delete();

        return redirect()
            ->route('admin.product-boms.index')
            ->with('success', 'فرمول ساخت حذف شد.');
    }

    /**
     * اعتبارسنجی‌های تجاری BOM
     */
    private function validateBomData(array $data): void
    {
        $productId = (int) $data['product_id'];

        foreach ($data['components'] as $component) {

            if (
                (int) $component['component_product_id']
                === $productId
            ) {
                abort(
                    422,
                    'دستگاه نمی‌تواند به عنوان قطعه خودش انتخاب شود.'
                );
            }

            $supplierIds = collect(
                $component['suppliers'] ?? []
            )
                ->pluck('supplier_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique();

            if ($supplierIds->isEmpty()) {
                continue;
            }

            $validCount = User::query()
                ->whereIn('id', $supplierIds)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'supplier');
                })
                ->count();

            if ($validCount !== $supplierIds->count()) {
                abort(
                    422,
                    'یکی از کاربران انتخاب‌شده تأمین‌کننده معتبر نیست.'
                );
            }

            if ($supplierIds->count() !== count(
                    $component['suppliers'] ?? []
                )) {
                abort(
                    422,
                    'یک تأمین‌کننده بیش از یک بار انتخاب شده است.'
                );
            }
        }
    }

    /**
     * همگام‌سازی تأمین‌کنندگان یک BOM
     */
    private function syncSuppliers(
        ProductBom $bom,
        array $suppliers
    ): void {

        $defaultFound = false;

        $bom->suppliers()->delete();

        foreach ($suppliers as $supplier) {

            $isDefault =
                !$defaultFound &&
                filter_var(
                    $supplier['is_default'] ?? false,
                    FILTER_VALIDATE_BOOLEAN
                );

            if ($isDefault) {
                $defaultFound = true;
            }

            $bom->suppliers()->create([
                'supplier_id' => $supplier['supplier_id'],
                'unit_price' => $supplier['unit_price'],
                'is_default' => $isDefault,
                'is_active' =>
                    filter_var(
                        $supplier['is_active'] ?? true,
                        FILTER_VALIDATE_BOOLEAN
                    ),
                'note' => $supplier['note'] ?? null,
            ]);
        }

        /*
         * اگر هیچ تأمین‌کننده‌ای به عنوان پیش‌فرض انتخاب نشده،
         * اولین تأمین‌کننده را پیش‌فرض می‌کنیم.
         */
        if (!$defaultFound && count($suppliers) > 0) {
            $bom->suppliers()
                ->orderBy('id')
                ->first()
                ?->update([
                    'is_default' => true,
                ]);
        }
    }
}