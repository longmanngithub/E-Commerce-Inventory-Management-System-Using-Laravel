<?php
namespace App\Observers;

use App\Models\Order;
use App\Notifications\LowStockWarning;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     * This will run automatically after a new order is saved to the database.
     */
    public function created(Order $order): void
    {
        // We only care about paid orders that reduce stock.
        if ($order->order_status === 'Paid') {
            // Loop through each item in the order
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                // After the stock is reduced by the order, check its new level
                $currentStock = $product->stocks()->sum('stock_quantity');
                $reorderPoint = $product->reorder_point;

                if ($currentStock <= $reorderPoint && $currentStock > 0) {
                    // If stock is low, find all company admins and notify them
                    $usersToNotify = $product->company->admins;
                    Notification::send($usersToNotify, new LowStockWarning($product));
                }
            }
        }
    }
}
