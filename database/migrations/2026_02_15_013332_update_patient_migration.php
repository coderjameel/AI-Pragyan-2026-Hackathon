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
            $table->integer('Systolic');

            $table->integer('Diastolic');

            $table->integer('HeartRate');

            $table->integer('RespiratoryRate');

            $table->integer('SpO2');

            $table->float('Temperature');
            //Mouth,Ear,Rectum,Armpit
            $table->enum('TempMethod', ['Mouth', 'Ear', 'Rectum', 'Armpit']);

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
