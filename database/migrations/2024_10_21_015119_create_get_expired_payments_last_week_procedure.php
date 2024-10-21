<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS GetExpiredPaymentsLastWeek;

            CREATE PROCEDURE GetExpiredPaymentsLastWeek()
            BEGIN
                SELECT payments.*, microsites.*
                FROM payments
                JOIN microsites ON microsites.id = payments.microsite_id
                WHERE microsites.type = "billing"
                AND payments.limit_date < NOW()
                AND payments.limit_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK);
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetExpiredPaymentsLastWeek');
    }
};
