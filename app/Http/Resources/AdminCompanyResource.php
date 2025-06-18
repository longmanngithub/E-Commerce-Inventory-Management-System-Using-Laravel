<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing(['admins', 'staff', 'subscription']);

        return [
            'id' => $this->company_id,
            'name' => $this->company_name,
            'email' => $this->company_email,
            'address' => $this->company_address,
            'website' => $this->company_website,
            'telephone' => $this->company_telephone,
            'imageUrl' => $this->company_image ? Storage::disk('public')->url($this->company_image) : null,

            'totalUsers' => $this->admins->count() + $this->staff->count(),
            'subscription' => $this->whenLoaded('subscription', fn() => [
                'plan' => $this->subscription->subscription_tier,
                'status' => $this->subscription->is_paid ? 'Paid' : 'Unpaid',
                'nextBillingCycle' => $this->subscription->renew_date,
            ]),
        ];
    }
}
