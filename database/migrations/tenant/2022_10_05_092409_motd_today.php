<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MotdToday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $sql = "CREATE OR REPLACE VIEW motd_todays " 
            . " AS select * "
            . " FROM `motds` WHERE `publication_date` <= CURDATE() and (CURDATE() < `end_date` or `end_date` IS NULL)";
        \DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $sql = "DROP VIEW IF EXISTS `motd_todays` ";
        \DB::statement($sql);
    }
}
