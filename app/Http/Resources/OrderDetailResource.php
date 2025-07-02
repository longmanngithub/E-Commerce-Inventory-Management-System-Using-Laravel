<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->order_id,
            'date' => $this->order_date,
            'status' => $this->order_status,
            'totalAmount' => number_format($this->total_amount, 2),

            'customer' => $this->whenLoaded('customer', fn() => [
                'name' => $this->customer->customer_name,
                'email' => $this->customer->customer_email,
                'phone' => $this->customer->customer_phone,
                'imageUrl' => $this->customer->customer_image,
            ]),

            'items' => $this->whenLoaded('orderItems', fn() => $this->orderItems->map(fn($item) => [
                'name' => $item->product->product_name,
                'imageUrl' => $item->product->product_image ? Storage::disk('public')->url($item->product->product_image) : null,
                'sku' => $item->product->product_SKU,
                'category' => $item->product->category->category_name,
                'quantity' => $item->order_item_quantity,
                'price' => number_format($item->order_item_unit_price, 2),
                'subtotal' => number_format($item->order_item_quantity * $item->order_item_unit_price, 2),
            ])),
        ];
    }
}
