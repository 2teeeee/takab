<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use App\Services\InventoryTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreSaleController extends Controller
{
    public function index(Request $request): View
    {
        $authUser = Auth::user();

         // فقط فروشگاه‌ها

        if (!$authUser->hasRole(['admin', 'manager', 'personel'])) {
            $storesQuery->where('registered_by', $authUser->id);
        }

        if ($request->filled('search')) {

            $search = trim($request->search);

            $storesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('national_code', 'like', "%{$search}%");
            });
        }

        $stores = $storesQuery
            ->with('roles')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('store.list', compact('stores'));
    }

    public function create(User $store): View
    {
        $locale = app()->getLocale();
        $user = auth()->user();

        /*
         * تعیین عمده فروش
         *
         * عمده فروش:
         * خودش صاحب موجودی است.
         *
         * بازاریاب:
         * موجودی را از عمده فروش خودش دریافت می‌کند.
         */
        $wholesalerId = match (true) {

            $user->hasRole('wholesaler') => $user->id,

            $user->hasRole('marketer') => $user->wholesaler_id,

            default => null,
        };

        if (!$wholesalerId) {
            abort(403, 'عمده فروش برای این کاربر مشخص نشده است.');
        }

        $products = ProductUser::query()
            ->where('product_user.user_id', $wholesalerId)
            ->where('product_user.quantity', '>', 0)

            ->join(
                'products',
                'products.id',
                '=',
                'product_user.product_id'
            )

            ->join('product_translations as t', function ($join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })

            ->join('product_images as image', function ($join) {
                $join->on('image.product_id', '=', 'products.id')
                    ->where('image.is_main', 1);
            })

            ->select([
                'products.id',
                't.title',
                'products.sell_price',
                'image.small_image_name',
                'product_user.quantity as stock',
            ])

            ->get();

        return view(
            'wholesaler.store.sale',
            compact('store', 'products')
        );
    }

    public function store(
        Request $request,
        User $store,
        InventoryTransferService $inventoryTransferService
    ): RedirectResponse {

        $request->validate([
            'address' => ['required', 'string', 'max:1000'],
            'products' => ['required', 'array'],
        ]);

        $selected = collect($request->products)
            ->filter(fn ($q) => (int) $q > 0);

        if ($selected->isEmpty()) {
            return back()
                ->withErrors([
                    'products' => 'حداقل یک محصول باید انتخاب شود.'
                ])
                ->withInput();
        }

        $user = auth()->user();

        if ($user->hasRole('wholesaler')) {
            $wholesalerId = $user->id;
        } elseif ($user->hasRole('marketer')) {
            $wholesalerId = $user->wholesaler_id;
            if (!$wholesalerId) {
                return back()
                    ->withErrors([
                        'products' => 'عمده فروش مربوط به بازاریاب مشخص نشده است.'
                    ])
                    ->withInput();
            }
        } else {
            abort(403);
        }

        $order = $inventoryTransferService->transfer(
            fromUserId: $wholesalerId,
            toUserId: $store->id,
            products: $request->products,
            address: $request->address,
        );

        $order->update([
            'wholesaler_id' => $wholesalerId,
            'seller_id' => $user->id,
            'seller_role' => 'wholesaler',
        ]);

        if ($user->hasRole('wholesaler')) {
            $inventoryTransferService->approve($order);
        }

        return redirect()
            ->route('wholesaler.stores.index')
            ->with('success', 'سفارش ثبت شد.');
    }

    public function sales(Request $request): View
    {
        $storeId = auth()->id();

        $query = Order::with([
            'user',
            'items.product.translation',
        ])
            ->where('seller_id', $storeId)
            ->where('seller_role', 'store')
            ->latest();

        // جستجو
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', $search)

                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");

                    });

            });
        }

        // وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'store.sales.index',
            compact('orders')
        );
    }
}
