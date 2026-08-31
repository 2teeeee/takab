<?php

namespace App\Http\Controllers;

use App\Models\WalletWithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletWithdrawalController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function index(Request $request): View
    {
        $wallet = $request->user()->getOrCreateWallet();

        $requests = $request->user()
            ->walletWithdrawalRequests()
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'wallet.withdrawals.index',
            compact(
                'wallet',
                'requests'
            )
        );
    }


    public function create(Request $request): View
    {
        $wallet = $request->user()->getOrCreateWallet();

        return view(
            'wallet.withdrawals.create',
            compact('wallet')
        );
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],

            'card_number' => [
                'nullable',
                'string',
                'digits:16',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'sheba_number' => [
                'nullable',
                'string',
                'size:24',
            ],

            'account_holder_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $user = $request->user();

        $wallet = $user->getOrCreateWallet();

        if ($validated['amount'] > $wallet->balance) {
            return back()
                ->withErrors([
                    'amount' =>
                        'مبلغ درخواست بیشتر از موجودی کیف پول است.'
                ])
                ->withInput();
        }

        /*
         * Prevent multiple pending withdrawal requests.
         */
        $hasPendingRequest = $user
            ->walletWithdrawalRequests()
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return back()
                ->withErrors([
                    'amount' =>
                        'شما یک درخواست برداشت در حال بررسی دارید.'
                ])
                ->withInput();
        }

        $walletWithdrawal = $user->walletWithdrawalRequests()->create([
            'wallet_id' => $wallet->id,
            'amount' => $validated['amount'],
            'card_number' => $validated['card_number'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'sheba_number' => $validated['sheba_number'] ?? null,
            'account_holder_name' => $validated['account_holder_name'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        $this->walletService->debit(
            user: $user,
            amount: $walletWithdrawal->amount,
            description: 'واریز به حساب بانکی',
            reference: $walletWithdrawal
        );

        return redirect()
            ->route('wallet.withdrawals.index')
            ->with(
                'success',
                'درخواست واریز با موفقیت ثبت شد و در انتظار بررسی مدیریت است.'
            );
    }

    public function indexAdmin(Request $request): View
    {
        $withdrawals = WalletWithdrawalRequest::query()
            ->with([
                'user',
                'wallet',
                'processor',
            ])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->status
                )
            )
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {

                    $search = trim($request->search);

                    $query->where(function ($query) use ($search) {

                        $query->where('card_number', 'like', "%{$search}%")
                            ->orWhere(
                                'account_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'sheba_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'payment_tracking_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'user',
                                function ($userQuery) use ($search) {
                                    $userQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'mobile',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    });
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'wallet.admin.index',
            compact('withdrawals')
        );
    }


    /**
     * Display withdrawal request details.
     */
    public function show(
        WalletWithdrawalRequest $withdrawal
    ): View {

        $withdrawal->load([
            'user',
            'wallet',
            'processor',
        ]);

        return view(
            'wallet.admin.show',
            compact('withdrawal')
        );
    }


    /**
     * Approve a withdrawal request.
     */
    public function approve(
        WalletWithdrawalRequest $withdrawal
    ): RedirectResponse {

        abort_if(
            $withdrawal->status !== 'pending',
            422,
            'این درخواست قبلاً بررسی شده است.'
        );

        $withdrawal->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()
            ->route('admin.wallet.withdrawals.show', $withdrawal)
            ->with(
                'success',
                'درخواست واریز با موفقیت تایید شد.'
            );
    }


    /**
     * Reject a withdrawal request.
     */
    public function reject(
        Request $request,
        WalletWithdrawalRequest $withdrawal
    ): RedirectResponse {

        $validated = $request->validate([
            'admin_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        abort_if(
            $withdrawal->status !== 'pending',
            422,
            'این درخواست قبلاً بررسی شده است.'
        );

        $withdrawal->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        $this->walletService->credit(
            user: $withdrawal->user,
            amount: $withdrawal->amount,
            description: 'بازگشت به دلیل رد درخواست واریز',
            reference: $withdrawal
        );

        return redirect()
            ->route('admin.wallet.withdrawals.show', $withdrawal)
            ->with(
                'success',
                'درخواست واریز رد شد.'
            );
    }


    /**
     * Mark an approved withdrawal as paid.
     */
    public function paid(
        Request $request,
        WalletWithdrawalRequest $withdrawal
    ): RedirectResponse {

        $validated = $request->validate([
            'payment_tracking_code' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        abort_if(
            $withdrawal->status !== 'approved',
            422,
            'فقط درخواست‌های تایید شده قابل ثبت به عنوان پرداخت شده هستند.'
        );

        $withdrawal->update([
            'status' => 'paid',
            'payment_tracking_code' => $validated[
            'payment_tracking_code'
            ],
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('admin.wallet.withdrawals.show', $withdrawal)
            ->with(
                'success',
                'پرداخت با موفقیت ثبت شد.'
            );
    }
}