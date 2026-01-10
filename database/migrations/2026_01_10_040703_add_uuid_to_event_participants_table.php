<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });
    }

    public function down()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
