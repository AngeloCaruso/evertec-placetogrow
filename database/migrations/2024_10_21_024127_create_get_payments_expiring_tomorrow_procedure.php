<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
