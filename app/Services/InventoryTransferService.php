<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryTransferService
{
    /**
     * انتقال موجودی
     *
     * @param int $fromUserId
     * @param int $toUserId
     * @param array $products [product_id => quantity]
     * @param int $discountPerItem
     * @param string $address
     */
    public function transfer(
        int $fromUserId,
        int $toUserId,
        array $products,
        int $discountPerItem = 0,
        string $address = '-'
    ): Order {

        return DB::transaction(function () use (
            $fromUserId,
            $toUserId,
            $products,
            $discountPerItem,
            $address
        ) {

            $order = Order::create([
                'user_id'     => $toUserId,
                'status'      => 'pending',
                'address'     => $address,
                'total'       => 0,
                'discount'    => 0,
                'final_total' => 0,
            ]);

            $total = 0;
            $discount = 0;

            foreach ($products as $productId => $quantity) {

                $quantity = (int) $quantity;

                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($productId);

                $inventory = ProductUser::query()
                    ->where('user_id', $fromUserId)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory) {
                    throw new RuntimeException("موجودی کالا یافت نشد.");
                }

                if ($inventory->quantity < $quantity) {
                    throw new RuntimeException(
                        "{$product->translate()->title} فقط {$inventory->quantity} عدد موجود است."
                    );
                }

                $price = $product->sell_price;
                $rowTotal = $price * $quantity;

                $order->items()->create([
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'total'      => $rowTotal,
                ]);

                ProductUser::decrease(
                    $fromUserId,
                    $productId,
                    $quantity
                );

                ProductUser::increase(
                    $toUserId,
                    $productId,
                    $quantity
                );

                $total += $rowTotal;

                $discount += $quantity * $discountPerItem;
            }

            if ($order->items()->count() == 0) {
                throw new RuntimeException('هیچ محصولی انتخاب نشده است.');
            }

            $order->update([
                'total'       => $total,
                'discount'    => $discount,
                'final_total' => max(0, $total - $discount),
            ]);

            return $order;
        });
    }
}