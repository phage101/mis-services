<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeSignatureNullableInClientSatisfactionFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL to modify column to be nullable
        // This avoids requiring doctrine/dbal for simple column modification
        DB::statement('ALTER TABLE client_satisfaction_feedbacks MODIFY signature TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE client_satisfaction_feedbacks MODIFY signature TEXT NOT NULL');
    }
}
