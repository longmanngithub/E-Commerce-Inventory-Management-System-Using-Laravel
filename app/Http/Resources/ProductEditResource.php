<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $latestStock = $this->stocks()->latest('stock_purchase_date')->first();

        return [
            'id' => $this->product_id,
            'name' => $this->product_name,
            'sku' => $this->product_SKU,
            'price' => (float) $this->product_price,
            'description' => $this->product_desc,
            'reorderPoint' => (int) $this->reorder_point,
            'expiryDate' => $this->product_expiry_date,
            'imageUrl' => $this->product_image ? Storage::url($this->product_image) : null,
            'categoryId' => $this->category_id,
            'totalStockQuantity' => $this->stocks->sum('stock_quantity'),

            'latestPurchase' => $latestStock ? [
                'price' => $latestStock->purchase_price,
                'date' => $latestStock->stock_purchase_date,
                'quantity' => $latestStock->stock_quantity,
            ] : null,
        ];
    }
}
