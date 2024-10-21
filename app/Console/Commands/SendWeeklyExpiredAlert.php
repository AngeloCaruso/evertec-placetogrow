<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Microsites\GetAllMicrositesWithAclAction;
use App\Enums\Notifications\EmailBody;
use App\Models\Microsite;
use App\Models\User;
use App\Notifications\PaymentExpiredReportNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendWeeklyExpiredAlert extends Command
{
    protected $signature = 'payments:expired-report';
    protected $description = 'Sends an email report of expired payments';

    public function handle()
    {
        $data = collect(DB::select('call GetExpiredPaymentsLastWeek()'));

        $users = User::get();

        foreach ($users as $user) {
            $siteIds = GetAllMicrositesWithAclAction::exec($user, new Microsite())->pluck('id');
            $filterSites = $data->whereIn('microsite_id', $siteIds);

            if ($filterSites->isEmpty()) {
                continue;
            }

            $user->notify(new PaymentExpiredReportNotification(EmailBody::ExpiredReport->value, $filterSites->groupBy('name')));
        }
    }
}
