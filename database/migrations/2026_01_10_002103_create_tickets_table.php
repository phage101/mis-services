<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->date('date_requested');
            $table->foreignId('requestor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_type_id')->constrained('request_types')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('complaint');
            $table->enum('urgency', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->dateTime('datetime_started')->nullable();
            $table->dateTime('datetime_ended')->nullable();
            $table->string('channel')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'on-going', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
