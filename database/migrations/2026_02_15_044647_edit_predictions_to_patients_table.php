<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('condition')->nullable()->change();
            $table->float('risk_score')->nullable()->change();
            $table->string('risk_level')->nullable()->change();
            $table->integer('Systolic')->nullable()->change();

            $table->integer('Diastolic')->nullable()->change();

            $table->integer('HeartRate')->nullable()->change();

            $table->integer('RespiratoryRate')->nullable()->change();

            $table->integer('SpO2')->nullable()->change();

            $table->float('Temperature')->nullable()->change();
            //Mouth,Ear,Rectum,Armpit
            $table->enum('TempMethod', ['Mouth', 'Ear', 'Rectum', 'Armpit'])->nullable()->change();

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
