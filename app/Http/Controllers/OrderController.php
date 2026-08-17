<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\Order;
use App\Services\InventoryTransferService;
use App\Services\Sms\NikSmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'LIKE', "%{$request->search}%")
                            ->orWhere('mobile', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if (Auth::user()->hasRole('seller')) {
            $query->where('seller_id', Auth::id());
        }

        $orders = $query
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(
            'items.product',
            'user',
            'seller',
            'fromUser',
        );

        return view('orders.show', compact('order'));
    }

    public function updateStatus(
        Request $request,
        Order $order,
        NikSmsService $nikSmsService
    ): RedirectResponse {
        $request->validate([
            'status' => ['required', 'string'],
            'status_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $newStatus = $request->status;
        $statusNote = $request->status_note;

        $oldStatus = $order->status;

        /*
        |--------------------------------------------------------------------------
        | Inventory transfer order
        |--------------------------------------------------------------------------
        */

        if ($order->from_user_id) {

            if ($order->status !== 'pending') {
                return back()->with(
                    'error',
                    __('orders.order_already_processed')
                );
            }

            if (!in_array($newStatus, ['success', 'rejected'])) {
                return back()->with(
                    'error',
                    __('orders.invalid_inventory_status')
                );
            }

            try {

                $service = app(InventoryTransferService::class);

                if ($newStatus === 'success') {
                    $service->approve($order);
                } else {
                    $service->reject($order);
                }

                /*
                 * Save status note
                 */
                $order->update([
                    'status_note' => $statusNote,
                ]);

                /*
                 * Create automation letter
                 */
                $this->createStatusChangeLetter(
                    $order,
                    $oldStatus,
                    $newStatus,
                    $statusNote,
                    $nikSmsService
                );

                return back()->with(
                    'success',
                    __('orders.status_updated')
                );

            } catch (\Throwable $e) {

                report($e);

                return back()->with(
                    'error',
                    $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normal order
        |--------------------------------------------------------------------------
        */

        $allowedStatuses = [
            'pending',
            'paid',
            'processing',
            'shipping',
            'delivered',
            'canceled',
        ];

        if (!in_array($newStatus, $allowedStatuses)) {
            return back()->with(
                'error',
                __('orders.invalid_status')
            );
        }

        $order->update([
            'status' => $newStatus,
            'status_note' => $statusNote,
        ]);

        /*
         * Create automation letter
         */
        $this->createStatusChangeLetter(
            $order,
            $oldStatus,
            $newStatus,
            $statusNote
        );

        return back()->with(
            'success',
            __('orders.status_updated')
        );
    }

    protected function createStatusChangeLetter(
        Order $order,
        string $oldStatus,
        string $newStatus,
        ?string $note,
        NikSmsService $sms
    ): void {
        $order->loadMissing([
            'user'
        ]);

        $statusLabels = [
            'pending'    => __('order.status.pending'),
            'paid'       => __('order.status.paid'),
            'processing' => __('order.status.processing'),
            'shipping'   => __('order.status.shipping'),
            'delivered'  => __('order.status.delivered'),
            'canceled'   => __('order.status.canceled'),
            'success'    => __('order.status.success'),
            'rejected'   => __('order.status.rejected'),
        ];

        $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
        $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;

        $actor = Auth::user();

        $buyerName = $order->user?->name ?? '—';

        $message = <<<TEXT
وضعیت سفارش شماره #{$order->id} تغییر کرد.

خریدار:
{$buyerName}

وضعیت قبلی:
{$oldStatusLabel}

وضعیت جدید:
{$newStatusLabel}

توضیح تغییر:
{$note}

مبلغ نهایی:
{$order->final_total} تومان

TEXT;

        $letter = Letter::create([
            'sender_id' => $actor->id,
            'subject'   => "تغییر وضعیت سفارش #{$order->id}",
            'body'      => $message,
            'priority'  => 'medium',
        ]);

        $letter->receiverItems()->create([
            'user_id' => $order->user_id,
            'status' => 'new',
            'last_received_at' => now(),
        ]);

        $message = <<<TEXT
یک نامه جدید برای شما ثبت شده است.

موضوع:
{$letter->subject}

{$letter->url}
TEXT;

        $sms->sendSingle(
            $order->user_id,
            $message
        );
    }

    public function purchases(Request $request): View
    {
        $user = auth()->user();

        $query = Order::query()
            ->with([
                'user',
                'fromUser',
                'wholesaler',
                'seller',
                'items.product.translation',
            ])
            ->where('user_id', $user->id)
            ->whereNotNull('from_user_id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', $search)

                    ->orWhereHas('fromUser', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");
                    })

                    ->orWhereHas('wholesaler', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");
                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('orders.purchases', compact('orders'));
    }
}
