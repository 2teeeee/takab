<?php

namespace App\Http\Controllers;

use App\Models\InstallRequest;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserInstallRequestController extends Controller
{
    public function index(): View
    {
        $requests = InstallRequest::where('user_id', auth()->id())
            ->with(['schedules.installer', 'periodicService'])
            ->latest()
            ->get();

        return view('profile.install_requests.index', compact('requests'));
    }

    public function create(): View
    {
        return view('profile.install_requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'device_model'   => 'required|string|max:255',
            'serial_number'  => 'nullable|string|max:255',
            'address'        => 'required|string|max:2000',
            'preferred_date' => 'nullable|date_format:Y-m-d\TH:i', // html datetime-local
        ]);

        $installationDate = null;
        if (!empty($data['preferred_date'])) {
            $installationDate = Carbon::createFromFormat('Y-m-d\TH:i', $data['preferred_date']);
        }

        InstallRequest::create([
            'user_id' => auth()->id(),
            'device_model' => $data['device_model'],
            'serial_number' => $data['serial_number'] ?? null,
            'address' => $data['address'],
            'installation_date' => $installationDate,
            'status' => 'pending',
        ]);

        return redirect()->route('profile.install_requests.index')
            ->with('success', 'درخواست شما با موفقیت ثبت شد. پس از بررسی توسط پشتیبانی با شما تماس گرفته خواهد شد.');
    }
}
