<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('diagnosis_condition')->nullable(); // The predicted disease
            $table->string('diagnosis_department')->nullable();
            $table->string('diagnosis_risk')->nullable();      // High/Mid/Low
            $table->float('diagnosis_score')->nullable();      // Confidence score
            $table->json('recent_symptoms')->nullable();       // Store selected symptoms
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
