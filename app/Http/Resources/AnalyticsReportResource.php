<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticsReportResource extends JsonResource
{
    // This tells Laravel not to wrap the response in an extra 'data' key.
    public static $wrap = 'data';

    // We will pass all our calculated data into the constructor.
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // The resource's data is already a pre-built array from the controller.
        // We just return it directly.
        return $this->resource;
    }
}
