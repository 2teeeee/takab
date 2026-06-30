<?php

namespace App\Http\Controllers;

use App\Models\PeriodicService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PeriodicServiceController extends Controller
{
    public function index(): View
    {
        $services = PeriodicService::with('installRequest.user')->orderBy('next_service_date')->paginate(10);
        return view('periodic_services.index', compact('services'));
    }

    public function update(Request $request, PeriodicService $periodicService): RedirectResponse
    {
        $data = $request->validate([
            'last_service_date' => 'required|date',
        ]);

        $periodicService->update([
            'last_service_date' => $data['last_service_date'],
            'next_service_date' => now()->parse($data['last_service_date'])->addMonths(6),
            'notified' => false,
        ]);

        return back()->with('success', 'تاریخ سرویس بروزرسانی شد.');
    }
}
