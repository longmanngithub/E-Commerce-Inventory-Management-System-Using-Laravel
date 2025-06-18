<?php

namespace App\Http\Resources;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource; // The model instance (Admin or Staff)

        // --- Dynamically determine all properties based on user type
        $role = 'Unknown';
        $userType = 'unknown';
        $name = 'Unknown User';
        $email = 'no-email@example.com';

        if ($user instanceof CompanyAdmin) {
            $role = $user->is_owner ? 'Company Owner' : 'Admin';
            $userType = 'admin';
            $name = $user->admin_name;
            $email = $user->admin_email;
        } elseif ($user instanceof CompanyStaff) {
            $role = 'Staff';
            $userType = 'staff';
            $name = $user->staff_name;
            $email = $user->staff_email;
        }

        return [
            'id' => $user->getKey(),
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'type' => $userType,
            'permissions' => $user->permissions ?? [],
        ];
    }
}
