<?php

namespace App\Http\Controllers;

use App\Models\Installer;
use App\Models\InstallReport;
use App\Models\InstallRequest;
use App\Models\InstallSchedule;
use App\Models\Order;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\Sms\NikSmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallRequestController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $requests = InstallRequest::with('user')->latest()->paginate(10);
        } else {
            $requests = InstallRequest::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('install_requests.index', compact('requests'));
    }

    public function create(): View
    {
        $users = User::all();
        return view('install_requests.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        InstallRequest::create($data);

        return redirect()->route('admin.install_requests.index')->with('success', 'درخواست نصب با موفقیت ثبت شد.');
    }

    public function edit(InstallRequest $installRequest): View
    {
        $users = User::all();
        return view('install_requests.edit', compact('installRequest', 'users'));
    }

    public function update(Request $request, InstallRequest $installRequest): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:pending,scheduled,installed,serviced,cancelled',
        ]);

        $installRequest->update($data);

        return redirect()->route('admin.install_requests.index')->with('success', 'درخواست نصب ویرایش شد.');
    }

    public function destroy(InstallRequest $installRequest): RedirectResponse
    {
        $installRequest->delete();
        return back()->with('success', 'درخواست حذف شد.');
    }

    public function createFromOrder(Order $order): View
    {
        $order->load('wholesaler', 'user');

        abort_unless(
            $order->wholesaler_id,
            422,
            'This order does not have a wholesaler.'
        );

        $installers = Installer::query()
            ->where('status', 'approved')
            ->whereHas('wholesalers', function ($query) use ($order) {
                $query->where(
                    'users.id',
                    $order->wholesaler_id
                );
            })
            ->with('user')
            ->get();

        /*
         * Select the installer registered by the order wholesaler
         * as the default installer.
         */
        $defaultInstallerId = $installers
            ->first(function (Installer $installer) use ($order) {
                return (int) $installer->user?->registered_by
                    === (int) $order->wholesaler_id;
            })
            ?->id;

        /*
         * Get purchased product models.
         * Remove duplicate models and join them with comma.
         */
        $deviceModel = $order->items
            ->map(function ($item) {
                return $item->product?->translation->title;
            })
            ->filter()
            ->unique()
            ->implode(', ');

        return view(
            'install_requests.create_for_order',
            compact(
                'order',
                'installers',
                'defaultInstallerId',
                'deviceModel'
            )
        );
    }

    public function storeFromOrder(
        Request $request,
        Order $order,
        NikSmsService $sms
    ): RedirectResponse {

        $validated = $request->validate([
            'installer_id' => [
                'required',
                'exists:installers,id',
            ],

            'device_model' => [
                'required',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
                'max:2000',
            ],

            'scheduled_date' => [
                'required',
                'date',
            ],

            'description' => [
                'nullable',
            ],
        ]);

        /*
         * Make sure the selected installer belongs
         * to the wholesaler of this order.
         */
        $installer = Installer::query()
            ->where('id', $validated['installer_id'])
            ->where('status', 'approved')
            ->whereHas('wholesalers', function ($query) use ($order) {
                $query->where(
                    'users.id',
                    $order->wholesaler_id
                );
            })
            ->with('user')
            ->firstOrFail();

        /*
         * Load customer information.
         */
        $order->load('user');

        /*
         * Create installation request and schedule.
         */
        $installRequest = DB::transaction(function () use (
            $validated,
            $order,
            $installer
        ) {

            $installRequest = InstallRequest::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'wholesaler_id' => $order->wholesaler_id,
                'device_model' => $validated['device_model'],
                'serial_number' => $validated['serial_number'] ?? null,
                'address' => $validated['address'],
                'status' => 'scheduled',
                'description' => $validated['description'] ?? null,
            ]);


            InstallSchedule::create([
                /*
                 * install_schedules.installer_id references users.id
                 */
                'installer_id' => $installer->id,

                'install_request_id' => $installRequest->id,

                'scheduled_date' => $validated['scheduled_date'],

                'status' => 'waiting',
            ]);

            return $installRequest;
        });

        /*
         * Prepare installation date for SMS.
         */
        $scheduledDate = jdate(
            $validated['scheduled_date']
        )->format('Y/m/d');

        /*
         * Installer notification.
         */
        if (
            $installer->user &&
            !empty($installer->user->mobile)
        ) {

            $installerMessage =
                "نصاب گرامی،\n"
                . "یک درخواست نصب برای شما ثبت شد.\n"
                . "مدل دستگاه: {$validated['device_model']}\n"
                . "تاریخ مراجعه: {$scheduledDate}\n"
                . "آدرس: {$validated['address']}\n"
                . "شماره سفارش: {$order->id}";

            $sms->sendSingle(
                $installer->user->mobile,
                $installerMessage
            );
        }


        /*
         * Customer notification.
         */
        if (
            $order->user &&
            !empty($order->user->mobile)
        ) {

            $installerName = $installer->user?->name
                ?? 'نصاب';


            $customerMessage =
                "مشتری گرامی،\n"
                . "نصب دستگاه شما برنامه‌ریزی شد.\n"
                . "مدل دستگاه: {$validated['device_model']}\n"
                . "نصاب: {$installerName}\n"
                . "تاریخ مراجعه: {$scheduledDate}\n"
                . "شماره سفارش: {$order->id}";

            $sms->sendSingle(
                $order->user->mobile,
                $customerMessage
            );
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with(
                'success',
                'درخواست نصب با موفقیت ثبت و به نصاب اختصاص داده شد.'
            );
    }

    public function serviceRequests(Request $request): View
    {
        $query = InstallRequest::query()
            ->with([
                'user',
                'schedules.installer',
            ]);

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('device_model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });

            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
         * Requests scheduled for today are displayed first.
         * Then future requests, followed by older requests.
         */
        $query->orderByRaw("
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM install_schedules
                WHERE install_schedules.install_request_id = install_requests.id
                AND install_schedules.scheduled_date = CURDATE()
                AND install_schedules.status != 'cancelled'
            ) THEN 0

            WHEN EXISTS (
                SELECT 1
                FROM install_schedules
                WHERE install_schedules.install_request_id = install_requests.id
                AND install_schedules.scheduled_date > CURDATE()
                AND install_schedules.status != 'cancelled'
            ) THEN 1

            ELSE 2
        END
    ");

        $query->latest('install_requests.created_at');

        $requests = $query
            ->paginate(20)
            ->withQueryString();

        return view(
            'install_requests.service_requests',
            compact('requests')
        );
    }

    public function show(InstallRequest $installRequest): View
    {
        $installRequest->load([
            'user',
            'wholesaler',
            'schedules.installer',
            'schedules.report',
            'order',
        ]);

        return view(
            'install_requests.show',
            compact('installRequest')
        );
    }

    public function approveReport(InstallReport $report, CommissionService $commissionService): RedirectResponse {

        $report->load([
            'schedule.installRequest.order',
        ]);

        if ($report->status !== 'pending') {
            return back()->with(
                'error',
                'این گزارش قبلاً بررسی شده است.'
            );
        }

        if (!$report->completed) {
            return back()->with(
                'error',
                'این گزارش به عنوان انجام‌شده ثبت نشده است.'
            );
        }

        $order = $report
            ->schedule
            ->installRequest
            ->order;

        if (!$order) {
            return back()->with(
                'error',
                'سفارش مرتبط با این درخواست نصب پیدا نشد.'
            );
        }

        DB::transaction(function () use (
            $report,
            $order,
            $commissionService
        ) {

            // Approve installer report
            $report->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Create and pay installer commission
            $commissionService->createInstallerCommission(
                order: $order,
                installerId: $report->installer->user->id
            );
        });

        return back()->with(
            'success',
            'گزارش با موفقیت تأیید شد و پورسانت نصاب به کیف پول او واریز گردید.'
        );
    }
}
