<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->company_id,
            'name' => $this->company_name,
            'image_url' => $this->company_image ? asset('storage/' . $this->company_image) : null,
            'status' => $this->status,
            'subscription' => [
                'plan' => optional($this->subscription)->subscription_tier,
                'status' => optional($this->subscription)->is_paid ? 'Paid' : 'Unpaid',
                'next_billing_date' => optional($this->subscription)->renew_date,
            ],
            'registered_on' => $this->register_date,
        ];
    }
}
