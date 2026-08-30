<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class WalletService
{
    public function credit(
        User $user,
        int $amount,
        ?string $description = null,
        ?Model $reference = null
    ): WalletTransaction {

        if ($amount <= 0) {
            throw new RuntimeException(
                'مبلغ تراکنش باید بیشتر از صفر باشد.'
            );
        }

        return DB::transaction(function () use (
            $user,
            $amount,
            $description,
            $reference
        ) {

            $wallet = Wallet::query()
                ->firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0]
                );

            $wallet = Wallet::query()
                ->where('id', $wallet->id)
                ->lockForUpdate()
                ->first();

            /*
             * Prevent duplicate credit for the same reference.
             */
            if ($reference) {

                $exists = WalletTransaction::query()
                    ->where('wallet_id', $wallet->id)
                    ->where('reference_type', $reference->getMorphClass())
                    ->where('reference_id', $reference->getKey())
                    ->where('type', 'credit')
                    ->exists();

                if ($exists) {
                    throw new RuntimeException(
                        'این کمیسیون قبلاً به کیف پول منتقل شده است.'
                    );
                }
            }

            $before = $wallet->balance;

            $after = $before + $amount;

            $wallet->update([
                'balance' => $after,
            ]);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'transaction_code' => $this->generateTransactionCode(),
            ]);
        });
    }


    public function debit(
        User $user,
        int $amount,
        ?string $description = null,
        ?Model $reference = null
    ): WalletTransaction {

        if ($amount <= 0) {
            throw new RuntimeException(
                'مبلغ تراکنش باید بیشتر از صفر باشد.'
            );
        }

        return DB::transaction(function () use (
            $user,
            $amount,
            $description,
            $reference
        ) {

            $wallet = Wallet::query()
                ->firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0]
                );

            $wallet = Wallet::query()
                ->where('id', $wallet->id)
                ->lockForUpdate()
                ->first();

            if ($wallet->balance < $amount) {
                throw new RuntimeException(
                    'موجودی کیف پول کافی نیست.'
                );
            }

            $before = $wallet->balance;

            $after = $before - $amount;

            $wallet->update([
                'balance' => $after,
            ]);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'transaction_code' => $this->generateTransactionCode(),
            ]);
        });
    }


    protected function generateTransactionCode(): string
    {
        do {
            $code = 'WAL-' . strtoupper(Str::random(12));
        } while (
            WalletTransaction::where(
                'transaction_code',
                $code
            )->exists()
        );

        return $code;
    }
}