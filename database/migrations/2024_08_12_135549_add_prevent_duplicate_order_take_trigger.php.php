<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::unprepared('
                CREATE TRIGGER prevent_duplicate_order_take
                BEFORE INSERT ON order_takes
                FOR EACH ROW
                BEGIN
                    DECLARE duplicate_count INT;

                    SELECT COUNT(*) INTO duplicate_count
                    FROM order_takes
                    WHERE invoice_id = NEW.invoice_id
                      AND ABS(TIMESTAMPDIFF(MINUTE, created_at, NEW.created_at)) <= 5;

                    IF duplicate_count > 0 THEN
                        SIGNAL SQLSTATE "45000"
                            SET MESSAGE_TEXT = "Duplicate entry for order_takes within 5 minutes";
                    END IF;
                END
            ');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::unprepared('DROP TRIGGER IF EXISTS prevent_duplicate_order_take');
        }
    }
};
