<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->logAction('created', $product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->logAction('updated', $product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->logAction('deleted', $product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }

    protected function logAction(string $action, Product $product): void
    {
        // Check for ANY authenticated user.
        if (Auth::check()) {
            $user = Auth::user();

            $log = new AuditLog([
                'action' => ucfirst($action),
                'details' => "Product '{$product->product_name}' (SKU: {$product->product_SKU}) was {$action}.",
                'timestamp' => now(),
            ]);

            // Associate the log with the product that was changed.
            $log->subject()->associate($product);

            // Associate the log with the user who performed the action.
            $log->user()->associate($user);

            $log->save();
        }
    }
}
