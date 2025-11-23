<?php

namespace App\Http\Controllers;

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
}
