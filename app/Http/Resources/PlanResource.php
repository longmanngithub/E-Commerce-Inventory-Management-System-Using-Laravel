<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->subscription_id,
            'tier' => $this->subscription_tier,
            'price' => $this->subscription_price,
            'productLimit' => $this->product_limit,
        ];
    }
}
