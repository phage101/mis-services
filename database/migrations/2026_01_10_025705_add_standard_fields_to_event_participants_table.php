<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStandardFieldsToEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('event_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('organization')->nullable()->after('last_name');
            $table->string('designation')->nullable()->after('organization');
            $table->string('age_bracket')->nullable()->after('designation');
            $table->string('sex')->nullable()->after('age_bracket');
            $table->string('province')->nullable()->after('sex');
            $table->string('contact_no')->nullable()->after('province');
            $table->string('email')->nullable()->after('contact_no');
        });
    }

    public function down()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'organization', 'designation', 'age_bracket', 'sex', 'province', 'contact_no', 'email']);
        });
    }
}
