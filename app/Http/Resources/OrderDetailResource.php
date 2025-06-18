<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'totalAmount' => $this->total_amount,

            'customer' => $this->whenLoaded('customer', fn() => [
                'name' => $this->customer->customer_name,
                'email' => $this->customer->customer_email,
                'phone' => $this->customer->customer_phone,
                'imageUrl' => $this->customer->customer_image,
            ]),

            'items' => $this->whenLoaded('orderItems', fn() => $this->orderItems->map(fn($item) => [
                'name' => $item->product->product_name,
                'sku' => $item->product->product_SKU,
                'quantity' => $item->order_item_quantity,
                'price' => $item->order_item_unit_price,
                'subtotal' => $item->order_item_quantity * $item->order_item_unit_price,
            ])),
        ];
    }
}
