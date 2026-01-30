<?php

namespace App\Http\Resources;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            $imageUrl = $user->admin_image ? Storage::url($user->admin_image) : null;
        } elseif ($user instanceof CompanyStaff) {
            $role = 'Staff';
            $userType = 'staff';
            $name = $user->staff_name;
            $email = $user->staff_email;
            $imageUrl = $user->staff_image ? Storage::url($user->staff_image) : null;
        }

        return [
            'id' => $user->getKey(),
            'imageUrl' => $imageUrl,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'type' => $userType,
            'permissions' => $user->permissions ?? [],
        ];
    }
}
