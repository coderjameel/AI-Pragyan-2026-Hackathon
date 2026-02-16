<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Models\Patient;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- DASHBOARD (Updated with Analytics) ---
Route::get('/dashboard', function () {

    // PAGINATED TABLE
    $patientList = Patient::latest()->paginate(10);

    // LIVE QUEUE
    $queue = Patient::where('status', 'waiting')
        ->orderByDesc('triage_score')
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($patient) {
            return $patient->diagnosis_department ?? 'General';
        });

    // HISTORY
    $history = Patient::where('status', 'treated')
        ->latest()
        ->limit(10)
        ->get();

    // OPTIMIZED STATS
    $total = Patient::count();

    $riskCounts = Patient::select('risk_level', DB::raw('count(*) as total'))
        ->groupBy('risk_level')
        ->pluck('total', 'risk_level');

    $waiting = Patient::where('status', 'waiting')->count();

    $criticalWaiting = Patient::where('risk_level', 'High')
        ->where('status', 'waiting')
        ->count();

    $stats = [
        'total'       => $total,
        'waiting'     => $waiting,
        'critical'    => $criticalWaiting,
        'high_risk'   => $riskCounts['High'] ?? 0,
        'medium_risk' => $riskCounts['Medium'] ?? 0,
        'low_risk'    => $riskCounts['Low'] ?? 0,
    ];

    // CHART DATA
    $disease_data = Patient::select('condition', DB::raw('count(*) as total'))
        ->whereNotNull('condition')
        ->where('condition', '!=', '')
        ->groupBy('condition')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    $chart_labels = $disease_data->pluck('condition')->toArray();
    $chart_values = $disease_data->pluck('total')->toArray();

    return view('dashboard', compact(
        'patientList',
        'queue',
        'history',
        'stats',
        'chart_labels',
        'chart_values'
    ));

})->middleware(['auth', 'verified'])->name('dashboard');





Route::middleware('auth')->group(function () {

    // --- PROFILE ROUTES ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PATIENT MANAGEMENT ---
    Route::get("/add-patient", [PatientController::class, 'getPatientForm'])->name('add-patient');
    Route::post("/add-patient", [PatientController::class, 'addPatient']);

    // --- ANALYZE VIEW (Risk + Differential Diagnosis) ---
    Route::get("/analyze/{pid}", function ($pid) {
        $data = Patient::findOrFail($pid);

        $vitals = null;
        if ($data->Systolic) {
            $vitals = [
                'Systolic' => $data->Systolic,
                'Diastolic' => $data->Diastolic,
                'HeartRate' => $data->HeartRate,
                'RespiratoryRate' => $data->RespiratoryRate,
                'SpO2' => $data->SpO2,
                'Temperature' => $data->Temperature,
                'TempMethod' => $data->TempMethod,
            ];
        }

        // Full Symptom List for Search
        $symptoms = [
            "anxiety and nervousness", "depression", "shortness of breath", "depressive or psychotic symptoms",
            "sharp chest pain", "dizziness", "insomnia", "abnormal involuntary movements", "chest tightness",
            "palpitations", "irregular heartbeat", "breathing fast", "hoarse voice", "sore throat",
            "difficulty speaking", "cough", "nasal congestion", "throat swelling", "diminished hearing",
            "lump in throat", "throat feels tight", "difficulty in swallowing", "skin swelling", "retention of urine",
            "groin mass", "leg pain", "hip pain", "suprapubic pain", "blood in stool", "lack of growth",
            "emotional symptoms", "elbow weakness", "back weakness", "pus in sputum", "symptoms of the scrotum and testes",
            "swelling of scrotum", "pain in testicles", "flatulence", "pus draining from ear", "jaundice",
            "mass in scrotum", "white discharge from eye", "irritable infant", "abusing alcohol", "fainting",
            "hostile behavior", "drug abuse", "sharp abdominal pain", "feeling ill", "vomiting", "headache",
            "nausea", "diarrhea", "vaginal itching", "vaginal dryness", "painful urination", "involuntary urination",
            "pain during intercourse", "frequent urination", "lower abdominal pain", "vaginal discharge",
            "blood in urine", "hot flashes", "intermenstrual bleeding", "hand or finger pain", "wrist pain",
            "hand or finger swelling", "arm pain", "wrist swelling", "arm stiffness or tightness", "arm swelling",
            "hand or finger stiffness or tightness", "wrist stiffness or tightness", "lip swelling", "toothache",
            "abnormal appearing skin", "skin lesion", "acne or pimples", "dry lips", "facial pain", "mouth ulcer",
            "skin growth", "eye deviation", "diminished vision", "double vision", "cross-eyed", "symptoms of eye",
            "pain in eye", "eye moves abnormally", "abnormal movement of eyelid", "foreign body sensation in eye",
            "irregular appearing scalp", "swollen lymph nodes", "back pain", "neck pain", "low back pain",
            "pain of the anus", "pain during pregnancy", "pelvic pain", "impotence", "infant spitting up",
            "vomiting blood", "regurgitation", "burning abdominal pain", "restlessness", "symptoms of infants",
            "wheezing", "peripheral edema", "neck mass", "ear pain", "jaw swelling", "mouth dryness", "neck swelling",
            "knee pain", "foot or toe pain", "bowlegged or knock-kneed", "ankle pain", "bones are painful",
            "knee weakness", "elbow pain", "knee swelling", "skin moles", "knee lump or mass", "weight gain",
            "problems with movement", "knee stiffness or tightness", "leg swelling", "foot or toe swelling",
            "heartburn", "smoking problems", "muscle pain", "infant feeding problem", "recent weight loss",
            "problems with shape or size of breast", "underweight", "difficulty eating", "scanty menstrual flow",
            "vaginal pain", "vaginal redness", "vulvar irritation", "weakness", "decreased heart rate",
            "increased heart rate", "bleeding or discharge from nipple", "ringing in ear", "plugged feeling in ear",
            "itchy ear(s)", "frontal headache", "fluid in ear", "neck stiffness or tightness", "spots or clouds in vision",
            "eye redness", "lacrimation", "itchiness of eye", "blindness", "eye burns or stings", "itchy eyelid",
            "feeling cold", "decreased appetite", "excessive appetite", "excessive anger", "loss of sensation",
            "focal weakness", "slurring words", "symptoms of the face", "disturbance of memory", "paresthesia",
            "side pain", "fever", "shoulder pain", "shoulder stiffness or tightness", "shoulder weakness",
            "arm cramps or spasms", "shoulder swelling", "tongue lesions", "leg cramps or spasms",
            "abnormal appearing tongue", "ache all over", "lower body pain", "problems during pregnancy",
            "spotting or bleeding during pregnancy", "cramps and spasms", "upper abdominal pain", "stomach bloating",
            "changes in stool appearance", "unusual color or odor to urine", "kidney mass", "swollen abdomen",
            "symptoms of prostate", "leg stiffness or tightness", "difficulty breathing", "rib pain", "joint pain",
            "muscle stiffness or tightness", "pallor", "hand or finger lump or mass", "chills", "groin pain",
            "fatigue", "abdominal distention", "regurgitation.1", "symptoms of the kidneys", "melena", "flushing",
            "coughing up sputum", "seizures", "delusions or hallucinations", "shoulder cramps or spasms",
            "joint stiffness or tightness", "pain or soreness of breast", "excessive urination at night",
            "bleeding from eye", "rectal bleeding", "constipation", "temper problems", "coryza", "wrist weakness",
            "eye strain", "hemoptysis", "lymphedema", "skin on leg or foot looks infected", "allergic reaction",
            "congestion in chest", "muscle swelling", "pus in urine", "abnormal size or shape of ear",
            "low back weakness", "sleepiness", "apnea", "abnormal breathing sounds", "excessive growth",
            "elbow cramps or spasms", "feeling hot and cold", "blood clots during menstrual periods",
            "absence of menstruation", "pulling at ears", "gum pain", "redness in ear", "fluid retention",
            "flu-like syndrome", "sinus congestion", "painful sinuses", "fears and phobias", "recent pregnancy",
            "uterine contractions", "burning chest pain", "back cramps or spasms", "stiffness all over",
            "muscle cramps, contractures, or spasms", "low back cramps or spasms", "back mass or lump", "nosebleed",
            "long menstrual periods", "heavy menstrual flow", "unpredictable menstruation", "painful menstruation",
            "infertility", "frequent menstruation", "sweating", "mass on eyelid", "swollen eye", "eyelid swelling",
            "eyelid lesion or rash", "unwanted hair", "symptoms of bladder", "irregular appearing nails",
            "itching of skin", "hurts to breath", "nailbiting", "skin dryness, peeling, scaliness, or roughness",
            "skin on arm or hand looks infected", "skin irritation", "itchy scalp", "hip swelling",
            "incontinence of stool", "foot or toe cramps or spasms", "warts", "bumps on penis", "too little hair",
            "foot or toe lump or mass", "skin rash", "mass or swelling around the anus", "low back swelling",
            "ankle swelling", "hip lump or mass", "drainage in throat", "dry or flaky scalp",
            "premenstrual tension or irritability", "feeling hot", "feet turned in", "foot or toe stiffness or tightness",
            "pelvic pressure", "elbow swelling", "elbow stiffness or tightness", "early or late onset of menopause",
            "mass on ear", "bleeding from ear", "hand or finger weakness", "low self-esteem", "throat irritation",
            "itching of the anus", "swollen or red tonsils", "irregular belly button", "swollen tongue", "lip sore",
            "vulvar sore", "hip stiffness or tightness", "mouth pain", "arm weakness", "leg lump or mass",
            "disturbance of smell or taste", "discharge in stools", "penis pain", "loss of sex drive",
            "obsessions and compulsions", "antisocial behavior", "neck cramps or spasms", "pupils unequal",
            "poor circulation", "thirst", "sleepwalking", "skin oiliness", "sneezing", "bladder mass",
            "knee cramps or spasms", "premature ejaculation", "leg weakness", "posture problems", "bleeding in mouth",
            "tongue bleeding", "change in skin mole size or color", "penis redness", "penile discharge",
            "shoulder lump or mass", "polyuria", "cloudy eye", "hysterical behavior", "arm lump or mass",
            "nightmares", "bleeding gums", "pain in gums", "bedwetting", "diaper rash", "lump or mass of breast",
            "vaginal bleeding after menopause", "infrequent menstruation", "mass on vulva", "jaw pain",
            "itching of scrotum", "postpartum problems of the breast", "eyelid retracted", "hesitancy",
            "elbow lump or mass", "muscle weakness", "throat redness", "joint swelling", "tongue pain",
            "redness in or around nose", "wrinkles on skin", "foot or toe weakness", "hand or finger cramps or spasms",
            "back stiffness or tightness", "wrist lump or mass", "skin pain", "low back stiffness or tightness",
            "low urine output", "skin on head or neck looks infected", "stuttering or stammering",
            "problems with orgasm", "nose deformity", "lump over jaw", "sore in nose", "hip weakness",
            "back swelling", "ankle stiffness or tightness", "ankle weakness", "neck weakness"
        ];

        if ($data->diagnosis_condition) {
            // Manually flash to session so the view displays it automatically
            session()->now('result', [
                'disease' => $data->diagnosis_condition,
                'department' => $data->diagnosis_department,
                'risk_type' => $data->diagnosis_risk,
                'risk_score' => $data->diagnosis_score
            ]);

            if($data->recent_symptoms) {
                session()->now('selected_symptoms', json_decode($data->recent_symptoms));
            }
        }

        // ... [Rest of route] ...
        return view("analyzePatient", compact('data', 'vitals', 'symptoms'));
    })->name("analyze");

    // --- ADD VITALS (Auto-Trigger AI) ---
    Route::post("/add-vitals/{pid}", function (Request $request, $pid) {
        $validated = $request->validate([
            'Systolic' => 'required|integer|min:50|max:250',
            'Diastolic' => 'required|integer|min:30|max:150|lt:Systolic',
            'HeartRate' => 'required|integer|min:30|max:220',
            'RespiratoryRate' => 'required|integer|min:5|max:60',
            'SpO2' => 'required|integer|min:50|max:100',
            'Temperature' => 'required|numeric|min:30|max:45',
            'TempMethod' => 'required|string|in:Mouth,Ear,Rectum,Armpit'
        ]);

        $patient = Patient::findOrFail($pid);
        $patient->update([
            'Systolic' => $validated['Systolic'],
            'Diastolic' => $validated['Diastolic'],
            'HeartRate' => $validated['HeartRate'],
            'RespiratoryRate' => $validated['RespiratoryRate'],
            'SpO2' => $validated['SpO2'],
            'Temperature' => $validated['Temperature'],
            'TempMethod' => $validated['TempMethod']
        ]);
        try {
            $response = Http::timeout(4)->post("http://127.0.0.1:5000/predict", [
                "Age" => (int)$patient->age,
                "Gender" => ucwords(strtolower($patient->gender)),
                "Systolic" => (int)$patient->Systolic,
                "Diastolic" => (int)$patient->Diastolic,
                "HeartRate" => (int)$patient->HeartRate,
                "RespiratoryRate" => (int)$patient->RespiratoryRate,
                "SpO2" => (int)$patient->SpO2,
                "Temperature" => (float)$patient->Temperature,
                "TempMethod" => $patient->TempMethod
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $patient->condition = $result["prediction"]["condition"];
                $patient->risk_score = $result["prediction"]["risk_score"];
                $patient->risk_level = $result["prediction"]["risk_level"];
                $patient->status = 'waiting'; // Ensure patient is in the waiting queue
                $patient->save();

                $patient->calculateTriageScore(); // Update the queue position
                $patient->save();
                return back()->with('success', 'Vitals saved & Risk Analysis complete!');
            }
        } catch (\Exception $e) {
            return back()->with('warning', 'Vitals saved, but AI server is offline.');
        }

        return back()->with('success', 'Vitals saved successfully.');
    });

    // --- MANUAL RE-ANALYZE (Risk AI) ---
    Route::post("/predict/{pid}", function ($pid) {
        try {
            $patient = Patient::findOrFail($pid);
            if (!$patient->Systolic) return back()->with("error", "Please add vitals first.");

            $response = Http::timeout(5)->post("http://127.0.0.1:5000/predict", [
                "Age" => (int)$patient->age,
                "Gender" => ucwords(strtolower($patient->gender)),
                "Systolic" => (int)$patient->Systolic,
                "Diastolic" => (int)$patient->Diastolic,
                "HeartRate" => (int)$patient->HeartRate,
                "RespiratoryRate" => (int)$patient->RespiratoryRate,
                "SpO2" => (int)$patient->SpO2,
                "Temperature" => (float)$patient->Temperature,
                "TempMethod" => $patient->TempMethod
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $patient->condition = $result["prediction"]["condition"];
                $patient->risk_score = $result["prediction"]["risk_score"];
                $patient->risk_level = $result["prediction"]["risk_level"];
                $patient->status = 'waiting'; // Ensure patient is in the waiting queue
                $patient->save();

                $patient->calculateTriageScore(); // Update the queue position
                $patient->save();
                return back()->with("success", "Re-analysis complete.");
            }
            return back()->with("error", "AI Server Error.");

        } catch (\Exception $e) {
            return back()->with("error", "Connection failed: " . $e->getMessage());
        }
    });

    // --- DIFFERENTIAL DIAGNOSIS PREDICT ---
    Route::post('/diff-diagnosis-predict/{pid}', function (Request $request,$pid) {
        $selectedSymptoms = $request->input('symptoms', []);

        // Master List for alignment
        $allSymptoms = [
            "anxiety and nervousness", "depression", "shortness of breath", "depressive or psychotic symptoms",
            "sharp chest pain", "dizziness", "insomnia", "abnormal involuntary movements", "chest tightness",
            "palpitations", "irregular heartbeat", "breathing fast", "hoarse voice", "sore throat",
            "difficulty speaking", "cough", "nasal congestion", "throat swelling", "diminished hearing",
            "lump in throat", "throat feels tight", "difficulty in swallowing", "skin swelling", "retention of urine",
            "groin mass", "leg pain", "hip pain", "suprapubic pain", "blood in stool", "lack of growth",
            "emotional symptoms", "elbow weakness", "back weakness", "pus in sputum", "symptoms of the scrotum and testes",
            "swelling of scrotum", "pain in testicles", "flatulence", "pus draining from ear", "jaundice",
            "mass in scrotum", "white discharge from eye", "irritable infant", "abusing alcohol", "fainting",
            "hostile behavior", "drug abuse", "sharp abdominal pain", "feeling ill", "vomiting", "headache",
            "nausea", "diarrhea", "vaginal itching", "vaginal dryness", "painful urination", "involuntary urination",
            "pain during intercourse", "frequent urination", "lower abdominal pain", "vaginal discharge",
            "blood in urine", "hot flashes", "intermenstrual bleeding", "hand or finger pain", "wrist pain",
            "hand or finger swelling", "arm pain", "wrist swelling", "arm stiffness or tightness", "arm swelling",
            "hand or finger stiffness or tightness", "wrist stiffness or tightness", "lip swelling", "toothache",
            "abnormal appearing skin", "skin lesion", "acne or pimples", "dry lips", "facial pain", "mouth ulcer",
            "skin growth", "eye deviation", "diminished vision", "double vision", "cross-eyed", "symptoms of eye",
            "pain in eye", "eye moves abnormally", "abnormal movement of eyelid", "foreign body sensation in eye",
            "irregular appearing scalp", "swollen lymph nodes", "back pain", "neck pain", "low back pain",
            "pain of the anus", "pain during pregnancy", "pelvic pain", "impotence", "infant spitting up",
            "vomiting blood", "regurgitation", "burning abdominal pain", "restlessness", "symptoms of infants",
            "wheezing", "peripheral edema", "neck mass", "ear pain", "jaw swelling", "mouth dryness", "neck swelling",
            "knee pain", "foot or toe pain", "bowlegged or knock-kneed", "ankle pain", "bones are painful",
            "knee weakness", "elbow pain", "knee swelling", "skin moles", "knee lump or mass", "weight gain",
            "problems with movement", "knee stiffness or tightness", "leg swelling", "foot or toe swelling",
            "heartburn", "smoking problems", "muscle pain", "infant feeding problem", "recent weight loss",
            "problems with shape or size of breast", "underweight", "difficulty eating", "scanty menstrual flow",
            "vaginal pain", "vaginal redness", "vulvar irritation", "weakness", "decreased heart rate",
            "increased heart rate", "bleeding or discharge from nipple", "ringing in ear", "plugged feeling in ear",
            "itchy ear(s)", "frontal headache", "fluid in ear", "neck stiffness or tightness", "spots or clouds in vision",
            "eye redness", "lacrimation", "itchiness of eye", "blindness", "eye burns or stings", "itchy eyelid",
            "feeling cold", "decreased appetite", "excessive appetite", "excessive anger", "loss of sensation",
            "focal weakness", "slurring words", "symptoms of the face", "disturbance of memory", "paresthesia",
            "side pain", "fever", "shoulder pain", "shoulder stiffness or tightness", "shoulder weakness",
            "arm cramps or spasms", "shoulder swelling", "tongue lesions", "leg cramps or spasms",
            "abnormal appearing tongue", "ache all over", "lower body pain", "problems during pregnancy",
            "spotting or bleeding during pregnancy", "cramps and spasms", "upper abdominal pain", "stomach bloating",
            "changes in stool appearance", "unusual color or odor to urine", "kidney mass", "swollen abdomen",
            "symptoms of prostate", "leg stiffness or tightness", "difficulty breathing", "rib pain", "joint pain",
            "muscle stiffness or tightness", "pallor", "hand or finger lump or mass", "chills", "groin pain",
            "fatigue", "abdominal distention", "regurgitation.1", "symptoms of the kidneys", "melena", "flushing",
            "coughing up sputum", "seizures", "delusions or hallucinations", "shoulder cramps or spasms",
            "joint stiffness or tightness", "pain or soreness of breast", "excessive urination at night",
            "bleeding from eye", "rectal bleeding", "constipation", "temper problems", "coryza", "wrist weakness",
            "eye strain", "hemoptysis", "lymphedema", "skin on leg or foot looks infected", "allergic reaction",
            "congestion in chest", "muscle swelling", "pus in urine", "abnormal size or shape of ear",
            "low back weakness", "sleepiness", "apnea", "abnormal breathing sounds", "excessive growth",
            "elbow cramps or spasms", "feeling hot and cold", "blood clots during menstrual periods",
            "absence of menstruation", "pulling at ears", "gum pain", "redness in ear", "fluid retention",
            "flu-like syndrome", "sinus congestion", "painful sinuses", "fears and phobias", "recent pregnancy",
            "uterine contractions", "burning chest pain", "back cramps or spasms", "stiffness all over",
            "muscle cramps, contractures, or spasms", "low back cramps or spasms", "back mass or lump", "nosebleed",
            "long menstrual periods", "heavy menstrual flow", "unpredictable menstruation", "painful menstruation",
            "infertility", "frequent menstruation", "sweating", "mass on eyelid", "swollen eye", "eyelid swelling",
            "eyelid lesion or rash", "unwanted hair", "symptoms of bladder", "irregular appearing nails",
            "itching of skin", "hurts to breath", "nailbiting", "skin dryness, peeling, scaliness, or roughness",
            "skin on arm or hand looks infected", "skin irritation", "itchy scalp", "hip swelling",
            "incontinence of stool", "foot or toe cramps or spasms", "warts", "bumps on penis", "too little hair",
            "foot or toe lump or mass", "skin rash", "mass or swelling around the anus", "low back swelling",
            "ankle swelling", "hip lump or mass", "drainage in throat", "dry or flaky scalp",
            "premenstrual tension or irritability", "feeling hot", "feet turned in", "foot or toe stiffness or tightness",
            "pelvic pressure", "elbow swelling", "elbow stiffness or tightness", "early or late onset of menopause",
            "mass on ear", "bleeding from ear", "hand or finger weakness", "low self-esteem", "throat irritation",
            "itching of the anus", "swollen or red tonsils", "irregular belly button", "swollen tongue", "lip sore",
            "vulvar sore", "hip stiffness or tightness", "mouth pain", "arm weakness", "leg lump or mass",
            "disturbance of smell or taste", "discharge in stools", "penis pain", "loss of sex drive",
            "obsessions and compulsions", "antisocial behavior", "neck cramps or spasms", "pupils unequal",
            "poor circulation", "thirst", "sleepwalking", "skin oiliness", "sneezing", "bladder mass",
            "knee cramps or spasms", "premature ejaculation", "leg weakness", "posture problems", "bleeding in mouth",
            "tongue bleeding", "change in skin mole size or color", "penis redness", "penile discharge",
            "shoulder lump or mass", "polyuria", "cloudy eye", "hysterical behavior", "arm lump or mass",
            "nightmares", "bleeding gums", "pain in gums", "bedwetting", "diaper rash", "lump or mass of breast",
            "vaginal bleeding after menopause", "infrequent menstruation", "mass on vulva", "jaw pain",
            "itching of scrotum", "postpartum problems of the breast", "eyelid retracted", "hesitancy",
            "elbow lump or mass", "muscle weakness", "throat redness", "joint swelling", "tongue pain",
            "redness in or around nose", "wrinkles on skin", "foot or toe weakness", "hand or finger cramps or spasms",
            "back stiffness or tightness", "wrist lump or mass", "skin pain", "low back stiffness or tightness",
            "low urine output", "skin on head or neck looks infected", "stuttering or stammering",
            "problems with orgasm", "nose deformity", "lump over jaw", "sore in nose", "hip weakness",
            "back swelling", "ankle stiffness or tightness", "ankle weakness", "neck weakness"
        ];

        $binaryVector = [];
        foreach($allSymptoms as $symptom) {
            $binaryVector[] = in_array($symptom, $selectedSymptoms) ? 1 : 0;
        }

        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:5000/diff-diagnosis-predict', [
                'symptoms' => $binaryVector
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $prediction = $result['prediction'];
                $patient = Patient::findOrFail($pid);

                $patient->diagnosis_condition = $prediction['disease'];
                $patient->diagnosis_department = $prediction['department'];
                $patient->diagnosis_risk = $prediction['risk_type'];
                $patient->diagnosis_score = $prediction['risk_score'];
                $patient->recent_symptoms = json_encode($selectedSymptoms); // Save symptoms too
                $patient->status = 'waiting'; // Ensure patient is in the waiting queue
                $patient->save();

                $patient->calculateTriageScore(); // <--- THIS UPDATES THE QUEUE POSITION
                $patient->save();
                return back()
                    ->with('result', $result['prediction'])
                    ->with('selected_symptoms', $selectedSymptoms);
            } else {
                return back()->with('error', 'AI Server Error: ' . $response->status());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Connection Failed: ' . $e->getMessage());
        }

    })->name('diff-diagnosis-predict');
});

require __DIR__.'/auth.php';
