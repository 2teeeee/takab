<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\ReferralCommission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Wallet
        |--------------------------------------------------------------------------
        */

        $wallet = $user->wallet;


        /*
        |--------------------------------------------------------------------------
        | My Orders
        |--------------------------------------------------------------------------
        */

        $ordersCount = Order::query()
            ->where('user_id', $user->id)
            ->count();


        $ordersTotal = Order::query()
            ->where('user_id', $user->id)
            ->sum('final_total');


        /*
        |--------------------------------------------------------------------------
        | Marketing Sales
        |--------------------------------------------------------------------------
        */

        $marketingOrdersCount = Order::query()
            ->where('moaref_id', $user->id)
            ->count();


        $marketingSalesTotal = Order::query()
            ->where('moaref_id', $user->id)
            ->sum('final_total');


        /*
        |--------------------------------------------------------------------------
        | Marketing Commission
        |--------------------------------------------------------------------------
        */

        $marketingCommission = ReferralCommission::query()
            ->where('user_id', $user->id)
            ->where('type', 'referral')
            ->whereIn('status', [
                'pending',
                'approved',
                'paid',
            ])
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Marketing Sales
        |--------------------------------------------------------------------------
        */

        $recentMarketingOrders = Order::query()
            ->where('moaref_id', $user->id)
            ->with([
                'commissions' => function ($query) use ($user) {
                    $query
                        ->where('user_id', $user->id)
                        ->where('type', 'referral');
                },
            ])
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Wallet Transactions
        |--------------------------------------------------------------------------
        */

        $recentTransactions = $wallet
            ? $wallet->transactions()
                ->latest()
                ->limit(5)
                ->get()
            : collect();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('profile.index', compact(
            'user',
            'wallet',
            'ordersCount',
            'ordersTotal',
            'marketingOrdersCount',
            'marketingSalesTotal',
            'marketingCommission',
            'recentOrders',
            'recentMarketingOrders',
            'recentTransactions',
        ));
    }

    public function edit(): View
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'اطلاعات با موفقیت به‌روزرسانی شد.');
    }

    public function editPassword(): View
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'رمز فعلی اشتباه است.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.index')->with('success', 'رمز عبور با موفقیت تغییر یافت.');
    }

    public function orders(): View
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function orderDetails(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'items.product',
        ]);


        return view('profile.order-details', compact('order'));
    }
}
