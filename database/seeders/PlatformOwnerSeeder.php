<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformOwner;
use Illuminate\Support\Facades\Hash;

class PlatformOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlatformOwner::create([
            'owner_name' => 'John Appleseed',
            'owner_email' => 'sandmen-gambols.7i@icloud.com',
            'owner_password' => Hash::make('12345678'),
        ]);
    }
}
