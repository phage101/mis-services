<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->date('date_requested');
            $table->foreignId('requestor_id')->constrained('users')->onDelete('cascade');
            $table->string('topic');
            $table->foreignId('platform_id')->nullable()->constrained('platforms')->onDelete('set null');
            $table->foreignId('host_id')->nullable()->constrained('hosts')->onDelete('set null');
            $table->enum('status', ['pending', 'scheduled', 'conflict', 'cancelled'])->default('pending');
            $table->text('meeting_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
