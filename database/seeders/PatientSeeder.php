<?php

// database/seeders/PatientSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $departments = ['Cardiology', 'Emergency Medicine', 'Neurology', 'Orthopedics', 'General Practice'];
        $risks = ['High', 'Medium', 'Low'];

        for ($i = 0; $i < 20; $i++) {
            $risk = $risks[array_rand($risks)];
            $dept = $departments[array_rand($departments)];

            // Logic: High risk usually has bad vitals
            $vitalsScore = $risk === 'High' ? rand(80, 99) : ($risk === 'Medium' ? rand(40, 79) : rand(10, 39));

            $p = Patient::create([
                'name' => $faker->name,
                'age' => rand(18, 90),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'Systolic' => rand(90, 180),
                'Diastolic' => rand(60, 120),
                'HeartRate' => rand(60, 120),
                'RespiratoryRate' => rand(12, 30),
                'SpO2' => rand(85, 100),
                'Temperature' => rand(36, 40),
                'TempMethod' => 'Mouth',
                'condition' => 'Simulated',
                'risk_level' => $risk,
                'risk_score' => $vitalsScore,
                'diagnosis_condition' => 'Simulated Disease',
                'diagnosis_department' => $dept,
                'diagnosis_risk' => $risk,
                'status' => 'waiting'
            ]);

            $p->calculateTriageScore(); // Calc priority immediately
        }
    }
}
