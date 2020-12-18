<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveWaitforplayersAndLiveStateEventTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE event_tournaments MODIFY status ENUM('DRAFT','OPEN','CLOSED','LIVE','COMPLETE') NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE event_tournaments MODIFY status ENUM('DRAFT','OPEN','CLOSED','PENDING','WAITFORPLAYERS','LIVE','COMPLETE') NOT NULL");


    }
}
