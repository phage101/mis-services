<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_participant_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_date_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('present'); // present, absent, late
            $table->text('remarks')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();

            $table->unique(['event_participant_id', 'event_date_id'], 'participant_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_attendances');
    }
}
