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
    /**
     * افزایش موجودی کیف پول
     */
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

            $wallet = $this->getLockedWallet($user);

            /*
             * جلوگیری از شارژ مجدد یک reference
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
                        'این مبلغ قبلاً به کیف پول منتقل شده است.'
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


    /**
     * کاهش موجودی کیف پول
     */
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

            $wallet = $this->getLockedWallet($user);

            /*
             * جلوگیری از پرداخت دوباره یک reference
             */
            if ($reference) {

                $exists = WalletTransaction::query()
                    ->where('wallet_id', $wallet->id)
                    ->where('reference_type', $reference->getMorphClass())
                    ->where('reference_id', $reference->getKey())
                    ->where('type', 'debit')
                    ->exists();

                if ($exists) {
                    throw new RuntimeException(
                        'این سفارش قبلاً از کیف پول پرداخت شده است.'
                    );
                }
            }

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


    /**
     * پرداخت سفارش از موجودی کیف پول
     *
     * این متد برای زمانی است که کاربر از قبل
     * در کیف پول خود موجودی دارد.
     */
    public function pay(
        User $user,
        int $amount,
        ?string $description = null,
        ?Model $reference = null
    ): WalletTransaction {

        return $this->debit(
            user: $user,
            amount: $amount,
            description: $description ?? 'پرداخت سفارش از کیف پول',
            reference: $reference
        );
    }


    /**
     * دریافت وجه خارجی و سپس پرداخت سفارش
     *
     * منبع وجه می‌تواند:
     *
     * gateway
     * cash
     *
     * باشد.
     *
     * ابتدا مبلغ وارد کیف پول می‌شود
     * و سپس از کیف پول بابت سفارش کسر می‌شود.
     */
    public function receiveAndPay(
        User $user,
        int $amount,
        string $source,
        ?Model $reference = null
    ): array {

        if ($amount <= 0) {
            throw new RuntimeException(
                'مبلغ پرداخت باید بیشتر از صفر باشد.'
            );
        }

        if (!in_array($source, ['online', 'cash'], true)) {
            throw new RuntimeException(
                'منبع پرداخت نامعتبر است.'
            );
        }

        return DB::transaction(function () use (
            $user,
            $amount,
            $source,
            $reference
        ) {

            /*
             * بسیار مهم:
             *
             * کیف پول را فقط یک بار قفل می‌کنیم
             * و هر دو عملیات داخل یک transaction
             * انجام می‌شوند.
             */
            $wallet = $this->getLockedWallet($user);

            /*
             * اگر reference داریم، بررسی می‌کنیم
             * که این پرداخت قبلاً پردازش نشده باشد.
             */
            if ($reference) {

                $alreadyProcessed = WalletTransaction::query()
                    ->where('wallet_id', $wallet->id)
                    ->where('reference_type', $reference->getMorphClass())
                    ->where('reference_id', $reference->getKey())
                    ->whereIn('type', ['credit', 'debit'])
                    ->exists();

                if ($alreadyProcessed) {
                    throw new RuntimeException(
                        'پرداخت این سفارش قبلاً پردازش شده است.'
                    );
                }
            }

            /*
             * ============================
             * 1. ورود پول به کیف پول
             * ============================
             */

            $beforeCredit = $wallet->balance;
            $afterCredit = $beforeCredit + $amount;

            $wallet->update([
                'balance' => $afterCredit,
            ]);

            $sourceTitle = match ($source) {
                'online' => 'شارژ کیف پول از طریق پرداخت آنلاین',
                'cash'   => 'شارژ کیف پول بابت دریافت وجه نقد',
            };

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $beforeCredit,
                'balance_after' => $afterCredit,
                'description' => $sourceTitle,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'transaction_code' => $this->generateTransactionCode(),
            ]);

            /*
             * ============================
             * 2. پرداخت سفارش از کیف پول
             * ============================
             */

            $beforeDebit = $wallet->balance;
            $afterDebit = $beforeDebit - $amount;

            $wallet->update([
                'balance' => $afterDebit,
            ]);

            $debitTransaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $beforeDebit,
                'balance_after' => $afterDebit,
                'description' => 'پرداخت سفارش از کیف پول',
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'transaction_code' => $this->generateTransactionCode(),
            ]);

            return [
                'credit' => $wallet->transactions()
                    ->latest('id')
                    ->where('type', 'credit')
                    ->first(),

                'debit' => $debitTransaction,
            ];
        });
    }


    /**
     * دریافت کیف پول و قفل کردن آن
     */
    protected function getLockedWallet(User $user): Wallet
    {
        $wallet = Wallet::query()
            ->where('user_id', $user->id)
            ->lockForUpdate()
            ->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);

            /*
             * بعد از ایجاد، مجدداً با lock دریافت شود.
             */
            $wallet = Wallet::query()
                ->where('id', $wallet->id)
                ->lockForUpdate()
                ->first();
        }

        return $wallet;
    }


    /**
     * تولید کد یکتای تراکنش
     */
    protected function generateTransactionCode(): string
    {
        do {

            $code = 'WAL-' . strtoupper(
                    Str::random(12)
                );

        } while (
            WalletTransaction::query()
                ->where('transaction_code', $code)
                ->exists()
        );

        return $code;
    }
}