<?php

namespace App\Http\Controllers;

use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReferralCommission::query()
            ->with([
                'user',
                'order',
                'paidBy',
            ]);

        /*
         * Search by commission ID, user name, or mobile.
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', $search)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");
                    });
            });
        }

        /*
         * Filter by commission type.
         */
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        /*
         * Filter by payment status.
         */
        if ($request->filled('is_paid')) {
            $query->where(
                'is_paid',
                (int) $request->is_paid
            );
        }

        /*
         * Filter by user.
         */
        if ($request->filled('user_id')) {
            $query->where(
                'user_id',
                $request->user_id
            );
        }

        /*
         * Filter by date range.
         */
        if ($request->filled('from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to
            );
        }

        /*
         * Calculate summary for the filtered records.
         */
        $totalAmount = (clone $query)
            ->sum('amount');

        $paidAmount = (clone $query)
            ->where('is_paid', true)
            ->sum('amount');

        $unpaidAmount = (clone $query)
            ->where('is_paid', false)
            ->sum('amount');

        $totalCount = (clone $query)
            ->count();

        /*
         * Users who have commissions.
         */
        $users = User::query()
            ->whereHas('commissions')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'mobile',
            ]);

        /*
         * Paginated commissions.
         */
        $commissions = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('commissions.index', compact(
            'commissions',
            'users',
            'totalAmount',
            'paidAmount',
            'unpaidAmount',
            'totalCount'
        ));
    }
}