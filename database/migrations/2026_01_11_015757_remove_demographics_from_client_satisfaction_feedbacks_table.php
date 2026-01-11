<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_satisfaction_feedbacks', function (Blueprint $table) {
            $table->dropColumn(['client_type', 'sex', 'age_bracket', 'email', 'contact_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_satisfaction_feedbacks', function (Blueprint $table) {
            $table->string('client_type');
            $table->string('sex')->nullable();
            $table->string('age_bracket')->nullable();
            $table->string('email');
            $table->string('contact_no')->nullable();
        });
    }
};
