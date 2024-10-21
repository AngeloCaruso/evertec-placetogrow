<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Microsites\GetAllMicrositesWithAclAction;
use App\Enums\Notifications\EmailBody;
use App\Models\Microsite;
use App\Models\User;
use App\Notifications\PaymentExpireTomorrowReportNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendDailyExpireTomorrowReport extends Command
{
    protected $signature = 'payments:expire-tomorrow-report';
    protected $description = 'Sends an email report of payments that will expire tomorrow';

    public function handle()
    {
        $data = collect(DB::select('call GetPaymentsExpiringTomorrow()'));

        $users = User::get();

        foreach ($users as $user) {
            $siteIds = GetAllMicrositesWithAclAction::exec($user, new Microsite())->pluck('id');
            $filterSites = $data->whereIn('microsite_id', $siteIds);

            if ($filterSites->isEmpty()) {
                continue;
            }

            $user->notify(new PaymentExpireTomorrowReportNotification(EmailBody::ExpiredReport->value, $filterSites->groupBy('name')));
        }
    }
}
