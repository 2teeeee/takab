<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $wallet = $user->getOrCreateWallet();

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'wallet.index',
            compact(
                    'user',
                'wallet',
                'transactions'
            )
        );
    }

    public function userWallet(User $user): View
    {
        $wallet = $user->getOrCreateWallet();

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'wallet.index_admin',
            compact(
                'user',
                'wallet',
                'transactions'
            )
        );
    }

}