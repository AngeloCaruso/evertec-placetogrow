<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS GetPaymentsExpiringTomorrow;

            CREATE PROCEDURE GetPaymentsExpiringTomorrow()
            BEGIN
                SELECT payments.*, microsites.*
                FROM payments
                JOIN microsites ON microsites.id = payments.microsite_id
                WHERE microsites.type = "billing"
                AND (
                    DATE(payments.limit_date) = CURDATE() + INTERVAL 1 DAY
                    OR
                    DATE(payments.limit_date) = CURDATE()
                );
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetPaymentsExpiringTomorrow');
    }
};
