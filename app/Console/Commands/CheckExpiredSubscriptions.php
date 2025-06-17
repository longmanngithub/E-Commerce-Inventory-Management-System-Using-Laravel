<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionOrder;
use Carbon\Carbon;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        // Find all paid subscriptions where the renewal date is in the past
        $expiredSubscriptions = SubscriptionOrder::where('is_paid', true)
            ->where('renew_date', '<', Carbon::now())
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No expired subscriptions found.');
            return 0;
        }

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->is_paid = false;
            $subscription->save();
            $this->info("Subscription for Company ID #{$subscription->company_id} has been marked as unpaid.");
        }

        $this->info('Finished checking subscriptions.');
        return 0;
    }
}
