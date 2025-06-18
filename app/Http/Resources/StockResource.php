<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stock_id' => $this->stock_id,
            'stock_quantity' => $this->stock_quantity,
            'purchase_price' => $this->purchase_price,
            'stock_purchase_date' => $this->stock_purchase_date,

            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
