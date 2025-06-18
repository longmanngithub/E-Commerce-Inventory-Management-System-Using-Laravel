<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customerName' => optional($this->customer)->customer_name,
            'customerEmail' => optional($this->customer)->customer_email,
            'date' => $this->order_date,
            'totalAmount' => $this->total_amount,
            'status' => $this->order_status,
        ];
    }
}
