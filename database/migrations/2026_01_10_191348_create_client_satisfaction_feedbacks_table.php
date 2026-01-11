<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSatisfactionFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_satisfaction_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            // Client Information
            $table->string('client_type');
            $table->string('sex');
            $table->string('age_bracket');
            $table->string('email');
            $table->string('contact_no');

            // Citizen's Charter Awareness
            $table->string('cc1_awareness'); // Yes, No
            $table->string('cc2_visibility'); // Easy to see, Somewhat easy to see, Difficult to see, Not applicable
            $table->string('cc3_helpfulness'); // Helped very much, Helped somewhat, Did not help, Not applicable

            // Service Ratings (1-5)
            $table->tinyInteger('rating_overall')->unsigned();
            $table->tinyInteger('rating_responsiveness')->unsigned();
            $table->tinyInteger('rating_reliability')->unsigned();
            $table->tinyInteger('rating_access_facilities')->unsigned();
            $table->tinyInteger('rating_communication')->unsigned();
            $table->tinyInteger('rating_costs')->unsigned(); // If applicable
            $table->tinyInteger('rating_integrity')->unsigned();
            $table->tinyInteger('rating_assurance')->unsigned();
            $table->tinyInteger('rating_outcome')->unsigned();
            $table->tinyInteger('rating_resource_speaker')->unsigned(); // If applicable

            // Comments and Suggestions
            $table->text('rating_remarks')->nullable(); // For NEITHER, DISAGREE, STRONGLY DISAGREE
            $table->text('comments')->nullable();

            // Consent and Signature
            $table->longText('signature'); // Base64 or path

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
        Schema::dropIfExists('client_satisfaction_feedbacks');
    }
}
