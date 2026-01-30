<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product_id,
            'categoryId' => $this->category_id,
            'name' => $this->product_name,
            'sku' => $this->product_SKU,
            'status' => $this->status,
            'price' => $this->product_price,
            'description' => $this->product_desc,
            'reorderPoint' => $this->reorder_point,
            'expiryDate' => $this->product_expiry_date,
            'imageUrl' => $this->product_image ? Storage::url($this->product_image) : null,
            'category' => optional($this->category)->category_name,
            'stockQuantity' => $this->whenLoaded('stocks', fn() => $this->stocks->sum('stock_quantity')),
            'stocks' => StockResource::collection($this->whenLoaded('stocks')),
            'stockStatus' => $this->stock_status,
            'permissions' => [
                'update' => $request->user()->can('update', $this->resource),
                'delete' => $request->user()->can('delete', $this->resource),
            ]
        ];
    }
}
