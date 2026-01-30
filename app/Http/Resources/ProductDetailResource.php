<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Calculate stats directly within the resource
        $monthlySales = \App\Models\OrderItem::where('product_id', $this->product_id)
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', now()->subDays(30)))
            ->sum('order_item_quantity');

        $latestStock = $this->stocks()->latest('stock_purchase_date')->first();

        return [
            'id' => $this->product_id,
            'name' => $this->product_name,
            'categoryId' => $this->category_id,
            'sku' => $this->product_SKU,
            'status' => $this->status,
            'price' => (float) $this->product_price,
            'description' => $this->product_desc,
            'reorderPoint' => (int) $this->reorder_point,
            'expiryDate' => $this->product_expiry_date,
            'imageUrl' => $this->product_image ? \Illuminate\Support\Facades\Storage::url($this->product_image) : null,
            'category' => optional($this->category)->category_name,
            'totalStockQuantity' => $this->stocks->sum('stock_quantity'),

            'latestPurchase' => $latestStock ? [
                'price' => $latestStock->purchase_price,
                'date' => $latestStock->stock_purchase_date,
            ] : null,

            'overviewStats' => [
                'currentStock' => $this->stocks->sum('stock_quantity'),
                'reorderPoint' => $this->reorder_point,
                'monthlySales' => $monthlySales,
            ],

            'permissions' => [
                'update' => $request->user()->can('update', $this->resource),
                'delete' => $request->user()->can('delete', $this->resource),
            ]
        ];
    }
}
