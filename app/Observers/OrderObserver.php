<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\LowStockWarning;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // We only care about paid orders that reduce stock.
        if ($order->order_status === 'Paid') {
            // Loop through each item in the order
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                // After the stock is reduced, check its level
                $currentStock = $product->stocks->sum('stock_quantity');
                $reorderPoint = $product->reorder_point;

                if ($currentStock <= $reorderPoint) {
                    // If stock is low, find the company owner and send the notification
                    $companyOwner = $product->company->admins()->where('is_owner', true)->first();
                    if ($companyOwner) {
                        $companyOwner->notify(new LowStockWarning($product));
                    }
                }
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
