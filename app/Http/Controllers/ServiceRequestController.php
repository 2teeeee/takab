<?php

namespace App\Http\Controllers;

use App\Models\InstallRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function index(): View
    {
        $requests = auth()->user()
            ->installRequests()
            ->with('order.items')
            ->latest()
            ->paginate(10);

        return view('profile.service-requests.index', compact('requests'));
    }

    public function show(InstallRequest $installRequest)
    {
        //
    }

    public function create(): View
    {
        $user = auth()->user();

        $devices = Order::query()
            ->where('user_id', $user->id)
            ->with('items.product')
            ->latest()
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) use ($order) {
                    return [
                        'product' => $item->product,
                        'order' => $order,
                        'item' => $item,
                        'device_model' => $item->product?->translation->title,
                    ];
                });
            })
            ->filter(fn ($device) => $device['product'])
            ->values();

        return view(
            'profile.service-requests.create',
            compact('devices')
        );
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'request_type' => [
                'required',
                'in:installation,service,repair',
            ],

            'device_id' => [
                'required',
            ],

            'address' => [
                'required',
                'string',
                'max:2000',
            ],

            'description' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify device belongs to current user
        |--------------------------------------------------------------------------
        */

        $device = Order::query()
            ->where('user_id', $user->id)
            ->whereHas('items', function ($query) use ($validated) {
                $query->where('id', $validated['device_id']);
            })
            ->with('items.product')
            ->first();


        if (!$device) {
            return back()
                ->withInput()
                ->with('error', 'دستگاه انتخاب‌شده متعلق به شما نیست.');
        }


        $orderItem = $device->items
            ->firstWhere('id', $validated['device_id']);


        $product = $orderItem?->product;


        if (!$product) {
            return back()
                ->withInput()
                ->with('error', 'اطلاعات دستگاه پیدا نشد.');
        }


        InstallRequest::create([
            'user_id' => $user->id,
            'order_id' => $device->id,
            'wholesaler_id' => $device->wholesaler_id,
            'request_type' => $validated['request_type'],
            'device_model' => $product->translation->title,
            'serial_number' => null,
            'address' => $validated['address'],
            'status' => 'pending',
            'description' => $validated['description'],
        ]);


        return redirect()
            ->route('profile.service-requests.create')
            ->with(
                'success',
                'درخواست شما با موفقیت ثبت شد و پس از بررسی با شما تماس گرفته خواهد شد.'
            );
    }
}