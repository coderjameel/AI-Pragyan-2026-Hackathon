<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Assessment - {{ $data->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.8);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --text-dark: #2d3748;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            min-height: 100vh;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        body::before, body::after { content: ''; position: absolute; border-radius: 50%; filter: blur(80px); z-index: -1; }
        body::before { background: rgba(102, 126, 234, 0.4); width: 400px; height: 400px; top: -100px; left: -100px; }
        body::after { background: rgba(118, 75, 162, 0.4); width: 300px; height: 300px; bottom: 50px; right: -50px; }

        .glass-card { background: var(--glass-bg); backdrop-filter: blur(12px); border: 1px solid var(--glass-border); border-radius: 24px; box-shadow: var(--glass-shadow); padding: 2rem; transition: transform 0.3s ease; }
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.5); padding: 1.5rem 0; margin-bottom: 3rem; }

        .vital-row { display: flex; align-items: center; justify-content: space-between; padding: 15px; border-radius: 16px; margin-bottom: 10px; background: rgba(255,255,255,0.5); }
        .vital-icon { width: 40px; height: 40px; border-radius: 12px; background: white; display: flex; align-items: center; justify-content: center; color: #667eea; font-size: 1.2rem; margin-right: 15px; }

        .btn-primary { background: var(--primary-gradient); border: none; border-radius: 50px; padding: 10px 24px; font-weight: 500; }
        .text-gradient { background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glass-badge { padding: 6px 14px; border-radius: 30px; font-weight: 600; font-size: 0.85rem; }
        .badge-High { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }
        .badge-Medium { background: rgba(255, 193, 7, 0.15); color: #856404; border: 1px solid rgba(255, 193, 7, 0.3); }
        .badge-Low { background: rgba(25, 135, 84, 0.1); color: #198754; border: 1px solid rgba(25, 135, 84, 0.2); }

        /* Search Components */
        .search-input { padding-left: 45px; border-radius: 50px; height: 50px; border: 1px solid rgba(255,255,255,0.5); background: rgba(255,255,255,0.6); }
        .search-icon { position: absolute; left: 18px; top: 14px; color: #718096; }
        .symptom-pool { max-height: 250px; overflow-y: auto; background: rgba(255,255,255,0.4); padding: 15px; border-radius: 12px; margin-top: 15px; }
        .symptom-pill { cursor: pointer; background: white; border: 1px solid rgba(0,0,0,0.05); border-radius: 20px; padding: 6px 14px; margin: 3px; display: inline-block; font-size: 0.85rem; }
        .symptom-pill.active { background: var(--primary-gradient); color: white; }
        .selected-box { min-height: 60px; background: white; border-radius: 12px; padding: 15px; border: 1px dashed #cbd5e0; }
    </style>
</head>
<body>

<div class="glass-header sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white p-2 rounded-circle shadow-sm"><i class="bi bi-person-fill fs-3 text-primary"></i></div>
            <div><h4 class="mb-0 fw-bold">{{ $data->name }}</h4><span class="text-muted small">ID: #{{ $data->id }}</span></div>
        </div>
        <div class="d-flex gap-4">
            <div class="text-end"><span class="d-block text-muted small fw-bold">AGE</span><span class="fs-5 fw-semibold">{{ $data->age }}</span></div>
            <div class="vr opacity-25"></div>
            <div class="text-end"><span class="d-block text-muted small fw-bold">GENDER</span><span class="fs-5 fw-semibold">{{ $data->gender }}</span></div>
        </div>
    </div>
</div>

<div class="container pb-5">

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i> {{ session('success') }}</div> @endif
    @if(session('warning')) <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-exclamation-triangle me-2"></i> {{ session('warning') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-x-circle me-2"></i> {{ session('error') }}</div> @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-gradient"><i class="bi bi-activity me-2"></i>Clinical Vitals</h5>

                    <button onclick="toggleEditMode()" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="bi bi-pencil me-1"></i>
                        <span id="btnText">{{ isset($vitals) ? 'Edit Values' : 'Add Vitals' }}</span>
                    </button>
                </div>

                <div id="vitalsDisplay" class="{{ $errors->any() || !isset($vitals) ? 'd-none' : '' }}">
                    @if(isset($vitals))
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon"><i class="bi bi-heart-pulse"></i></div><div><small class="text-muted d-block">BP</small><span class="fw-bold fs-5">{{ $vitals["Systolic"] }}/{{ $vitals["Diastolic"] }}</span></div></div>
                                </div>
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon"><i class="bi bi-heart"></i></div><div><small class="text-muted d-block">Heart Rate</small><span class="fw-bold fs-5">{{ $vitals["HeartRate"] }} <small class="text-muted fs-6">bpm</small></span></div></div>
                                </div>
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon"><i class="bi bi-lungs"></i></div><div><small class="text-muted d-block">Resp. Rate</small><span class="fw-bold fs-5">{{ $vitals["RespiratoryRate"] }}</span></div></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon text-danger"><i class="bi bi-droplet"></i></div><div><small class="text-muted d-block">SpO2</small><span class="fw-bold fs-5">{{ $vitals["SpO2"] }}%</span></div></div>
                                </div>
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon text-warning"><i class="bi bi-thermometer-half"></i></div><div><small class="text-muted d-block">Temp</small><span class="fw-bold fs-5">{{ $vitals["Temperature"] }}°C</span></div></div>
                                </div>
                                <div class="vital-row">
                                    <div class="d-flex align-items-center"><div class="vital-icon text-secondary"><i class="bi bi-rulers"></i></div><div><small class="text-muted d-block">Method</small><span class="fw-bold fs-6">{{ $vitals["TempMethod"] }}</span></div></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3 opacity-50"><i class="bi bi-clipboard-pulse display-1"></i></div>
                            <h5 class="text-muted fw-normal">No vitals recorded</h5>
                            <p class="small text-muted">Click the button above to add data.</p>
                        </div>
                    @endif
                </div>

                <form id="vitalsForm" method="POST" action="/add-vitals/{{ $data->id }}" class="{{ $errors->any() || !isset($vitals) ? '' : 'd-none' }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold text-muted">SYSTOLIC</label><input type="number" class="form-control" name="Systolic" value="{{ old('Systolic', $vitals['Systolic'] ?? '') }}" placeholder="120" required></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">DIASTOLIC</label><input type="number" class="form-control" name="Diastolic" value="{{ old('Diastolic', $vitals['Diastolic'] ?? '') }}" placeholder="80" required></div>
                        <div class="col-md-4"><label class="small fw-bold text-muted">HEART RATE</label><input type="number" class="form-control" name="HeartRate" value="{{ old('HeartRate', $vitals['HeartRate'] ?? '') }}" required></div>
                        <div class="col-md-4"><label class="small fw-bold text-muted">RESP RATE</label><input type="number" class="form-control" name="RespiratoryRate" value="{{ old('RespiratoryRate', $vitals['RespiratoryRate'] ?? '') }}" required></div>
                        <div class="col-md-4"><label class="small fw-bold text-muted">SPO2</label><input type="number" class="form-control" name="SpO2" value="{{ old('SpO2', $vitals['SpO2'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">TEMP</label><input type="number" step="0.1" class="form-control" name="Temperature" value="{{ old('Temperature', $vitals['Temperature'] ?? '') }}" required></div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">METHOD</label>
                            <select name="TempMethod" class="form-select" required>
                                <option value="">Select...</option>
                                @foreach(['Mouth', 'Ear', 'Rectum', 'Armpit'] as $m)
                                    <option value="{{ $m }}" {{ old('TempMethod', $vitals['TempMethod'] ?? '') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="button" onclick="toggleEditMode()" class="btn btn-light me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary shadow-sm">Save & Analyze</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="glass-card h-100" style="background: linear-gradient(145deg, rgba(255,255,255,0.9), rgba(240,249,255,0.9));">
                <div class="d-flex align-items-center mb-4 text-primary">
                    <i class="bi bi-stars fs-4 me-2"></i>
                    <h5 class="fw-bold mb-0">AI Vitals Analysis</h5>
                </div>

                @if($data->condition)
                    <div class="text-center">
                        <div class="mb-2"><span class="text-uppercase small fw-bold text-muted tracking-wide">PREDICTED CONDITION</span></div>
                        <h2 class="fw-bold text-dark mb-4 display-6">{{ $data->condition }}</h2>

                        <div class="row g-3 justify-content-center mb-4">
                            <div class="col-6">
                                <div class="bg-white p-3 rounded-4 shadow-sm border border-light">
                                    <small class="d-block text-muted mb-2 small fw-bold">RISK LEVEL</small>
                                    <span class="glass-badge badge-{{ ucfirst($data->risk_level) }}">{{ $data->risk_level }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white p-3 rounded-4 shadow-sm border border-light">
                                    <small class="d-block text-muted mb-2 small fw-bold">CONFIDENCE</small>
                                    <span class="fs-4 fw-bold text-dark">{{ $data->risk_score }}</span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="/predict/{{ $data->id }}">
                            @csrf
                            <button type="submit" class="btn btn-light border w-100 py-2 rounded-pill hover-shadow">
                                <i class="bi bi-arrow-clockwise me-2"></i>Re-Analyze Vitals
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="bg-white p-3 rounded-circle shadow-sm mb-3 d-inline-block text-primary"><i class="bi bi-cpu fs-1"></i></div>
                        <h5 class="fw-bold">Ready to Analyze</h5>
                        <p class="text-muted small px-3">Update patient vitals to generate a real-time risk assessment.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12">
            <div class="glass-card">
                <div class="row">
                    <div class="col-lg-7 border-end border-light pe-lg-4">
                        <h5 class="fw-bold mb-3 text-gradient"><i class="bi bi-search me-2"></i>Differential Diagnosis</h5>
                        <p class="text-muted small mb-3">Select observed symptoms to identify potential conditions.</p>

                        <form action="/diff-diagnosis-predict/{{ $data->id }}" method="POST">                            @csrf
                            <div class="position-relative mb-3">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="symptomSearch" class="form-control search-input" placeholder="Search symptoms (e.g., fever, cough)...">
                            </div>

                            <div class="selected-box mb-3" id="selectedContainer">
                                <span class="text-muted small fst-italic placeholder-text">No symptoms selected...</span>
                            </div>
                            <div id="hiddenInputs"></div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm mb-3">
                                <i class="bi bi-lightning-charge-fill me-2"></i>Predict Disease
                            </button>

                            <div class="symptom-pool" id="symptomPool">
                                @foreach($symptoms as $sym)
                                    <div class="symptom-pill" onclick="toggleSymptom(this, '{{ $sym }}')">{{ $sym }}</div>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5 ps-lg-4 d-flex align-items-center">
                        @if(session('result'))
                            <div class="w-100 p-2">
                                <div class="text-center mb-4">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill mb-2 px-3">
                                        AI Diagnosis
                                    </span>
                                    <h2 class="fw-bold text-dark mb-1">{{ session('result')['disease'] }}</h2>

                                    <div class="mt-2">
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 shadow-sm">
                                            <i class="bi bi-hospital me-1"></i> {{ session('result')['department'] ?? 'General' }}
                                        </span>
                                    </div>

                                    <div class="mt-2">
                                        <span class="glass-badge badge-{{ session('result')['risk_type'] }}">
                                            {{ session('result')['risk_type'] }} Risk
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold text-muted text-uppercase">Confidence Score</small>
                                        <small class="fw-bold">{{ session('result')['risk_score'] }}%</small>                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 10px; background-color: #e9ecef;">
                                        <div class="progress-bar {{ session('result')['risk_type'] == 'High' ? 'bg-danger' : (session('result')['risk_type'] == 'Medium' ? 'bg-warning' : 'bg-success') }}"
                                             role="progressbar"
                                             style="width: {{ min(session('result')['risk_score'], 100) }}%; border-radius: 10px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-3 rounded-4 border">
                                    <h6 class="fw-bold text-dark mb-2 border-bottom pb-2 small">
                                        <i class="bi bi-list-check me-2 text-primary"></i>Contributing Factors
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(session('selected_symptoms'))
                                            @foreach(session('selected_symptoms') as $s)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-check2 text-success me-1"></i> {{ ucfirst($s) }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted opacity-50 w-100">
                                <i class="bi bi-clipboard-data display-1"></i>
                                <p class="mt-3">Select symptoms to generate an<br>Explainable AI Report.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleEditMode() {
        const displayDiv = document.getElementById('vitalsDisplay');
        const formDiv = document.getElementById('vitalsForm');
        const btnText = document.getElementById('btnText');

        if (formDiv.classList.contains('d-none')) {
            // Show Form
            displayDiv.classList.add('d-none');
            formDiv.classList.remove('d-none');
            if(btnText) btnText.innerText = "Cancel";
        } else {
            // Hide Form
            formDiv.classList.add('d-none');
            displayDiv.classList.remove('d-none');
            if(btnText) btnText.innerText = "{{ isset($vitals) ? 'Edit Values' : 'Add Vitals' }}";
        }
    }

    const selectedContainer = document.getElementById('selectedContainer');
    const hiddenInputs = document.getElementById('hiddenInputs');
    const placeholder = document.querySelector('.placeholder-text');
    let selectedSet = new Set();

    @if(session('selected_symptoms'))
    const oldSelections = @json(session('selected_symptoms'));
    oldSelections.forEach(s => toggleSymptom(null, s, true));
    @endif

    function toggleSymptom(element, name, forceAdd=false) {
        if(!element) { const pills = document.querySelectorAll('#symptomPool .symptom-pill'); for(let p of pills) if(p.innerText === name) element = p; }

        if (selectedSet.has(name) && !forceAdd) {
            selectedSet.delete(name); if(element) element.classList.remove('active');
            const pillToRemove = selectedContainer.querySelector(`[data-name="${name}"]`); if(pillToRemove) pillToRemove.remove();
        } else {
            if(!selectedSet.has(name)) {
                selectedSet.add(name); if(element) element.classList.add('active');
                const pill = document.createElement('span'); pill.className = 'badge bg-primary me-1 mb-1 rounded-pill py-2 px-3 pointer';
                pill.innerHTML = `${name} <i class="bi bi-x ms-1"></i>`; pill.setAttribute('data-name', name);
                pill.onclick = () => toggleSymptom(element, name); selectedContainer.appendChild(pill);
            }
        }
        placeholder.style.display = selectedSet.size > 0 ? 'none' : 'block';
        hiddenInputs.innerHTML = ''; selectedSet.forEach(s => { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'symptoms[]'; input.value = s; hiddenInputs.appendChild(input); });
    }

    document.getElementById('symptomSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#symptomPool .symptom-pill').forEach(pill => { pill.style.display = pill.innerText.toLowerCase().includes(filter) ? 'inline-block' : 'none'; });
    });
</script>
</body>
</html>
