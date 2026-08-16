<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryTransferService
{
    /**
     * Create an inventory transfer order.
     *
     * During order creation:
     * - The sender's inventory is decreased.
     * - The receiver's inventory is not increased.
     * - The order is created with the given status.
     *
     * The receiver's inventory is increased only when
     * the order is explicitly approved.
     */
    public function transfer(
        int $fromUserId,
        int $toUserId,
        array $products,
        string $status = 'pending',
        int $discountPerItem = 0,
        string $address = '-',
        int $discount = 0,
    ): Order {

        return DB::transaction(function () use (
            $fromUserId,
            $toUserId,
            $status,
            $products,
            $discountPerItem,
            $address,
            $discount
        ) {

            /*
             * Only pending and success are valid initial states.
             */
            if (!in_array($status, ['pending', 'success'], true)) {
                throw new RuntimeException(
                    __('inventory.invalid_status')
                );
            }

            $order = Order::create([
                'user_id'       => $toUserId,
                'from_user_id'  => $fromUserId,
                'seller_id'     => Auth::id(),
                'status'        => $status,
                'address'       => $address,
                'total'         => 0,
                'discount'      => 0,
                'final_total'   => 0,
            ]);

            $total = 0;
            $discountCalc = 0;

            foreach ($products as $productId => $quantity) {

                $quantity = (int) $quantity;

                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($productId);

                /*
                 * Lock the sender's inventory to prevent
                 * concurrent transfers from using the same stock.
                 */
                $inventory = ProductUser::query()
                    ->where('user_id', $fromUserId)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory) {
                    throw new RuntimeException(
                        __('inventory.inventory_not_found')
                    );
                }

                if ($inventory->quantity < $quantity) {
                    throw new RuntimeException(
                        __('inventory.insufficient_inventory', [
                            'product'  => $product->translation->title,
                            'quantity' => $inventory->quantity,
                        ])
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

                /*
                 * Reserve the stock by decreasing the sender's inventory.
                 *
                 * The receiver's inventory is NOT increased here.
                 */
                ProductUser::decrease(
                    $fromUserId,
                    $productId,
                    $quantity
                );

                $total += $rowTotal;
                $discountCalc += $quantity * $discountPerItem;
            }

            if ($order->items()->count() === 0) {
                throw new RuntimeException(
                    __('inventory.no_products_selected')
                );
            }

            $discountFinal = $discount > 0
                ? $discount
                : $discountCalc;

            $order->update([
                'total'       => $total,
                'discount'    => $discountFinal,
                'final_total' => max(0, $total - $discountFinal),
            ]);

            return $order;
        });
    }


    /**
     * Approve the order and transfer inventory to the receiver.
     *
     * The receiver's inventory is increased only once.
     */
    public function approve(Order $order): Order
    {
        return DB::transaction(function () use ($order) {

            $order = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            /*
             * Only pending orders can be approved.
             *
             * This prevents the receiver's inventory
             * from being increased more than once.
             */
            if ($order->status !== 'pending') {
                throw new RuntimeException(
                    __('inventory.order_already_processed')
                );
            }

            if (!$order->from_user_id) {
                throw new RuntimeException(
                    __('inventory.from_user_not_defined')
                );
            }

            $toUserId = $order->user_id;

            foreach ($order->items as $item) {

                ProductUser::increase(
                    $toUserId,
                    $item->product_id,
                    $item->quantity
                );
            }

            $order->update([
                'status' => 'success',
            ]);

            return $order;
        });
    }

    /**
     * Reject the order and return the reserved inventory to the sender.
     */
    public function reject(Order $order): Order
    {
        return DB::transaction(function () use ($order) {

            $order = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            /*
             * Only pending orders can be rejected.
             *
             * This prevents the sender's inventory
             * from being restored more than once.
             */
            if ($order->status !== 'pending') {
                throw new RuntimeException(
                    __('inventory.order_already_processed')
                );
            }

            if (!$order->from_user_id) {
                throw new RuntimeException(
                    __('inventory.from_user_not_defined')
                );
            }

            $fromUserId = $order->from_user_id;

            foreach ($order->items as $item) {

                ProductUser::increase(
                    $fromUserId,
                    $item->product_id,
                    $item->quantity
                );
            }

            $order->update([
                'status' => 'rejected',
            ]);

            return $order;
        });
    }
}