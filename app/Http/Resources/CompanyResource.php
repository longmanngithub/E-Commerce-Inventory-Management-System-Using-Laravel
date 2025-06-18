<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'status' => $this->status,
            'imageUrl' => $this->company_image ? Storage::disk('public')->url($this->company_image) : null,
            'subscription' => [
                'plan' => optional($this->subscription)->subscription_tier,
                'status' => optional($this->subscription)->is_paid ? 'Paid' : 'Unpaid',
                'nextBillingDate' => optional($this->subscription)->renew_date,
            ],
            'registeredOn' => $this->register_date,
            'desc' => $this->company_desc,
        ];
    }
}
