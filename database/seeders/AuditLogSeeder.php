<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get ALL companies from the database
        $companies = Company::with('admins', 'staff', 'products')->get();

        if ($companies->isEmpty()) {
            $this->command->info('No companies found, skipping audit log seeding.');
            return;
        }

        // Loop through each company
        foreach ($companies as $company) {
            $this->command->info("Seeding audit logs for company: {$company->company_name}...");

            // Get users and products belonging to THIS specific company
            $users = $company->admins->concat($company->staff);
            $products = $company->products;

            if ($users->isEmpty() || $products->isEmpty()) {
                $this->command->info("Not enough users or products to create logs for {$company->company_name}, skipping.");
                continue; // Skip to the next company
            }

            // Create 15 sample log entries for THIS company
            for ($i = 0; $i < 15; $i++) {
                $user = $users->random();
                $product = $products->random();
                $action = ['Created', 'Updated', 'Deleted'][rand(0, 2)];

                $log = new AuditLog([
                    'action' => $action,
                    'entity_affected' => 'Product',
                    'details' => "User {$user->name} {$action} product '{$product->product_name}' (SKU: {$product->product_SKU})",
                    'timestamp' => now()->subHours(rand(1, 100)),
                ]);

                // Associate the log with the product that was changed
                $log->subject()->associate($product);
                // Associate the log with the user who performed the action
                $log->user()->associate($user);

                $log->save();
            }
        }

        $this->command->info('Sample audit logs have been created for all companies successfully!');
    }
}
