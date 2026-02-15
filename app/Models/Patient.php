<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name','age','gender','Systolic','Diastolic','HeartRate','RespiratoryRate','SpO2','Temperature','TempMethod', 'condition', 'risk_score', 'risk_level','diagnosis_condition', 'diagnosis_department', 'diagnosis_risk', 'diagnosis_score', 'recent_symptoms'];
    use HasFactory;
    protected $guarded = [];

    // The "Brain" of the Queue
    public function calculateTriageScore() {
        // 1. Start with Vitals Risk Score (from Python)
        // If not analyzed yet, default to 0
        $score = $this->risk_score ?? 0;

        // 2. Apply Severity Multiplier (Based on Vitals AI)
        if ($this->risk_level === 'High') {
            $score *= 1.5; // Huge boost for High Risk vitals
        } elseif ($this->risk_level === 'Medium') {
            $score *= 1.2;
        }

        // 3. Department Bonus (Only if Differential Diagnosis is done)
        // If Dept is null, we treat it as "General Practice" (No bonus)
        $critical_depts = ['Emergency Medicine', 'Cardiology', 'Neurology', 'Pulmonology'];

        if ($this->diagnosis_department && in_array($this->diagnosis_department, $critical_depts)) {
            $score += 20;
        }

        // 4. Save
        $this->triage_score = (int) $score;
        $this->saveQuietly();
    }
}
