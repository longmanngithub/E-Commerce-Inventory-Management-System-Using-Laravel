<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->log_id,
            'userName' => optional($this->user)->name, // Assumes a 'name' accessor
            'action' => $this->action,
            'entity' => $this->entity_affected,
            'detail' => $this->details,
            'timestamp' => $this->timestamp,
        ];
    }
}
