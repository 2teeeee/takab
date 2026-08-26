<?php

namespace App\Http\Controllers;

use App\Models\InstallReport;
use App\Models\InstallSchedule;
use App\Models\InstallRequest;
use App\Models\PeriodicService;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstallScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = InstallSchedule::with(['installer', 'installRequest.user'])
            ->latest()
            ->paginate(10);

        return view('install_schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $installers = User::all();
        $requests = InstallRequest::where('status', 'pending')->with('user')->get();

        return view('install_schedules.create', compact('installers', 'requests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'installer_id' => 'required|exists:users,id',
            'install_request_id' => 'required|exists:install_requests,id',
            'scheduled_date' => 'required|date',
        ]);

        $schedule = InstallSchedule::create($data);

        $schedule->installRequest->update([
            'status' => 'scheduled',
            'installation_date' => $data['scheduled_date'],
        ]);

        PeriodicService::create([
            'install_request_id' => $schedule->install_request_id,
            'last_service_date' => $data['scheduled_date'],
            'next_service_date' => Carbon::parse($data['scheduled_date'])->addMonths(6),
        ]);

        return redirect()->route('admin.install_schedules.index')->with('success', 'برنامه نصب ثبت شد.');
    }

    public function destroy(InstallSchedule $installSchedule): RedirectResponse
    {
        $installSchedule->delete();
        return back()->with('success', 'برنامه حذف شد.');
    }

    /**
     * Display installer service requests.
     */
    public function installerOrders(Request $request): View
    {
        $installerId = auth()->user()->installer->id;

        $schedules = InstallSchedule::query()
            ->where('installer_id', $installerId)
            ->with([
                'installRequest.user',
                'installer',
                'report',
            ])
            ->orderByRaw(
                "CASE
                    WHEN scheduled_date = CURDATE() THEN 0
                    WHEN scheduled_date > CURDATE() THEN 1
                    ELSE 2
                END"
            )
            ->orderBy('scheduled_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'install_schedules.installer_orders',
            compact('schedules')
        );
    }

    public function report(InstallSchedule $install_schedule): View
    {
        $install_schedule->load([
            'installer',
            'installRequest.user',
            'report',
        ]);

        abort_unless(
            $install_schedule->installer_id === auth()->user()->installer->id,
            403
        );

        return view(
            'install_schedules.report',
            compact('install_schedule')
        );
    }

    public function storeReport(Request $request, InstallSchedule $install_schedule): RedirectResponse {

        abort_unless(
            $install_schedule->installer_id === auth()->user()->installer->id,
            403
        );

        $validated = $request->validate([
            'completed' => [
                'required',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $report = InstallReport::updateOrCreate(
            [
                'install_schedule_id' => $install_schedule->id,
            ],
            [
                'installer_id' => auth()->user()->installer->id,
                'completed' => $validated['completed'],
                'description' => $validated['description'] ?? null,
            ]
        );

        if ($validated['completed']) {

            $install_schedule->update([
                'status' => 'done',
            ]);

            $install_schedule->installRequest()->update([
                'status' => 'serviced',
                'installation_date' => now(),
            ]);

        } else {

            $install_schedule->update([
                'status' => 'cancelled',
            ]);

            $install_schedule->installRequest()->update([
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('installer.orders.index')
            ->with(
                'success',
                'گزارش انجام کار با موفقیت ثبت شد.'
            );
    }

    public function showReport(InstallSchedule $install_schedule): View
    {
        $report = InstallReport::where(
            'install_schedule_id',
            $install_schedule->id
        )->first();

        return view(
            'install_schedules.show_report',
            compact('report')
        );
    }
}
