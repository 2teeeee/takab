<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionPlan;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class ProductionPlanController extends Controller
{
    public function index()
    {
        $plans = ProductionPlan::query()
            ->with([
                'product',
                'creator',
            ])
            ->withCount('requirements')
            ->latest()
            ->paginate(20);

        $statistics = [
            'total' => ProductionPlan::count(),

            'draft' => ProductionPlan::where(
                'status',
                'draft'
            )->count(),

            'planned' => ProductionPlan::where(
                'status',
                'planned'
            )->count(),

            'in_progress' => ProductionPlan::where(
                'status',
                'in_progress'
            )->count(),

            'completed' => ProductionPlan::where(
                'status',
                'completed'
            )->count(),
        ];

        return view(
            'admin.production-plans.index',
            compact('plans','statistics')
        );
    }

    public function create()
    {
        $locale = app()->getLocale();
        
        $products = Product::query()
            ->where('products.status', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })
            ->whereHas('boms', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('t.title')
            ->select([
                'products.id',
                't.title',
            ])->get();

        return view(
            'admin.production-plans.create',
            compact('products')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'start_date' => [
                'required',
                'string',
            ],

            'end_date' => [
                'required',
                'string',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ], [
            'product_id.required' => 'انتخاب دستگاه الزامی است.',
            'start_date.required' => 'تاریخ شروع تولید الزامی است.',
            'end_date.required' => 'تاریخ پایان تولید الزامی است.',
            'end_date.after_or_equal' =>
                'تاریخ پایان باید بعد از تاریخ شروع باشد.',
            'quantity.required' =>
                'میزان تولید الزامی است.',
            'quantity.min' =>
                'میزان تولید باید حداقل ۱ باشد.',
        ]);

        /*
         * بررسی اینکه دستگاه فرمول فعال دارد.
         */
        $product = Product::query()
            ->with([
                'boms' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->findOrFail($validated['product_id']);

        if ($product->boms->isEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'product_id' =>
                        'برای این دستگاه هیچ فرمول فعالی تعریف نشده است.'
                ]);
        }

        $plan = DB::transaction(function () use (
            $validated,
            $product
        ) {

            $startDate = Jalalian::fromFormat(
                'Y/m/d',
                $validated['start_date']
            )->toCarbon()->format('Y-m-d');


            $endDate = Jalalian::fromFormat(
                'Y/m/d',
                $validated['end_date']
            )->toCarbon()->format('Y-m-d');

            $plan = ProductionPlan::create([
                'product_id' => $product->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'quantity' => $validated['quantity'],
                'status' => 'draft',
                'note' => $validated['note'] ?? null,
                'created_by' => auth()->id(),
            ]);


            /*
             * تولید Requirements
             */
            foreach ($product->boms as $bom) {

                $requiredQuantity =
                    (float) $bom->quantity
                    * $validated['quantity'];


                $plan->requirements()->create([
                    'product_bom_id' => $bom->id,

                    'component_product_id' =>
                        $bom->component_product_id,

                    'required_per_unit' =>
                        $bom->quantity,

                    'required_quantity' =>
                        $requiredQuantity,

                    'available_quantity' => 0,

                    'purchase_quantity' =>
                        $requiredQuantity,

                    'unit_price' =>
                        $bom->unit_price,

                    'unit' =>
                        $bom->unit,

                    'status' => 'pending',
                ]);
            }


            return $plan;
        });

        return redirect()
            ->route(
                'admin.production-plans.show',
                $plan
            )
            ->with(
                'success',
                'برنامه تولید با موفقیت ایجاد شد.'
            );
    }

    public function show(ProductionPlan $productionPlan)
    {
        $productionPlan->load([
            'product',
            'creator',
            'requirements.componentProduct',
            'requirements.productBom',
        ]);

        return view(
            'admin.production-plans.show',
            compact('productionPlan')
        );
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:1000000',
            ],
        ]);


        $product = Product::query()
            ->with([
                'boms' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->with([
                            'componentProduct',
                            'suppliers' => function ($query) {
                                $query
                                    ->where('is_active', true)
                                    ->with('supplier');
                            },
                        ]);
                },
            ])
            ->findOrFail($validated['product_id']);


        $requirements = $product->boms
            ->map(function ($bom) use ($validated) {

                $requiredPerUnit = (float) $bom->quantity;

                $requiredQuantity =
                    $requiredPerUnit * (int) $validated['quantity'];

                $unitPrice = (int) $bom->unit_price;

                $estimatedCost =
                    $requiredQuantity * $unitPrice;


                $suppliers = $bom->suppliers
                    ->map(function ($bomSupplier) {

                        return [
                            'id' => $bomSupplier->supplier_id,

                            'name' =>
                                $bomSupplier->supplier?->name,

                            'mobile' =>
                                $bomSupplier->supplier?->mobile,

                            'unit_price' =>
                                (int) $bomSupplier->unit_price,

                            'is_default' =>
                                (bool) $bomSupplier->is_default,
                        ];

                    })
                    ->values();


                $defaultSupplier =
                    $bom->suppliers
                        ->firstWhere('is_default', true);


                return [

                    'component_product_id' =>
                        $bom->component_product_id,

                    'component_name' =>
                        $bom->componentProduct?->title,

                    'required_per_unit' =>
                        $requiredPerUnit,

                    'production_quantity' =>
                        (int) $validated['quantity'],

                    'required_quantity' =>
                        $requiredQuantity,

                    'unit' =>
                        $bom->unit,

                    'unit_price' =>
                        $unitPrice,

                    'estimated_cost' =>
                        $estimatedCost,

                    'default_supplier' =>
                        $defaultSupplier?->supplier?->name,

                    'suppliers' =>
                        $suppliers,
                ];
            })
            ->values();


        return response()->json([

            'product' => [
                'id' => $product->id,
                'title' => $product->title,
            ],

            'quantity' =>
                (int) $validated['quantity'],

            'requirements' =>
                $requirements,

            'total_estimated_cost' =>
                $requirements->sum('estimated_cost'),

            'items_count' =>
                $requirements->count(),

        ]);
    }
}
