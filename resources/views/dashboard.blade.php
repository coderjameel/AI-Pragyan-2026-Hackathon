<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            position: relative;
        }

        /* Animated Background Blobs */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px); z-index: -1;
        }
        body::before { background: rgba(102, 126, 234, 0.4); width: 500px; height: 500px; top: -100px; left: -100px; }
        body::after { background: rgba(118, 75, 162, 0.4); width: 400px; height: 400px; bottom: 50px; right: -50px; }

        /* Glass Components */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }
        .glass-card:hover { transform: translateY(-5px); }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding: 1.25rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        /* Typography & Buttons */
        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Table Styling */
        .glass-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .glass-table thead th {
            border: none; color: #718096; font-weight: 600; text-transform: uppercase;
            font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem;
        }
        .glass-table tbody tr {
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }
        .glass-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .glass-table td { padding: 1rem; vertical-align: middle; border: none; }
        .glass-table td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .glass-table td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

        /* Badges */
        .glass-badge { padding: 6px 12px; border-radius: 30px; font-weight: 600; font-size: 0.75rem; }
        .badge-High { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }
        .badge-Medium { background: rgba(255, 193, 7, 0.15); color: #856404; border: 1px solid rgba(255, 193, 7, 0.3); }
        .badge-Low { background: rgba(25, 135, 84, 0.1); color: #198754; border: 1px solid rgba(25, 135, 84, 0.2); }
        .badge-None { background: rgba(108, 117, 125, 0.1); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.2); }

        /* Loading Spinner for Infinite Scroll */
        .page-load-status {
            display: none; /* hidden by default */
            padding-top: 20px;
            padding-bottom: 20px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

<div class="glass-header sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                <i class="bi bi-hospital fs-4"></i>
            </div>
            <h4 class="mb-0 fw-bold text-dark">MediAI <span class="fw-light text-muted">Dashboard</span></h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-md-block">
                <span class="d-block text-muted small fw-bold">CURRENT USER</span>
                <span class="fs-6 fw-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-light rounded-circle shadow-sm border" title="Logout">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="container pb-5">

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block">Total Patients</span>
                    <span class="display-6 fw-bold text-primary">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block">High Risk</span>
                    <span class="display-6 fw-bold text-danger">{{ $stats['high_risk'] }}</span>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block">Medium Risk</span>
                    <span class="display-6 fw-bold text-warning">{{ $stats['medium_risk'] }}</span>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="bi bi-activity fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block">Low Risk</span>
                    <span class="display-6 fw-bold text-success">{{ $stats['low_risk'] }}</span>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="bi bi-shield-check-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-list-ol me-2"></i>Live Smart Queue</h4>
        </div>

        @forelse($queue as $dept => $patients)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 p-0 overflow-hidden border-0">
                    <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-primary">
                            {{ $dept ? $dept : 'Triage / Assessment' }}
                        </h6>
                        <span class="badge bg-primary rounded-pill">{{ $patients->count() }} Waiting</span>
                    </div>

                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @foreach($patients as $p)
                            <div class="list-group-item d-flex justify-content-between align-items-center
                            {{ $loop->first ? 'bg-warning bg-opacity-10' : '' }}"> <div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-dark me-2">#{{ $loop->iteration }}</span>
                                        <span class="fw-semibold">{{ $p->name }}</span>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        Waited: {{ $p->created_at->diffForHumans(null, true) }}
                                    </small>
                                </div>

                                <div class="text-end">
                                <span class="badge {{ $p->triage_score > 80 ? 'bg-danger' : ($p->triage_score > 50 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    Score: {{ $p->triage_score }}
                                </span>
                                    <div class="mt-1">
                                        <a href="/analyze/{{ $p->id }}" class="btn btn-xs btn-outline-primary py-0" style="font-size: 0.7rem;">Treat</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No patients in the waiting queue.</p>
            </div>
        @endforelse
    </div>

    <br>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Top 5 Conditions</h6>
                <canvas id="diseaseChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass-card h-100">
                <h6 class="fw-bold text-muted text-uppercase mb-4 text-center">Risk Distribution</h6>
                <div style="height: 200px; display: flex; justify-content: center;">
                    <canvas id="riskChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-gradient">Patient Records</h4>
        <a href="{{ route('add-patient') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg me-2"></i>Add Patient
        </a>
    </div>

    <div class="glass-card p-0 overflow-hidden">
        <div class="table-responsive p-3">
            <table class="glass-table" id="patientTable">
                <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age / Gender</th>
                    <th>Diagnosis</th>
                    <th>Risk Level</th>
                    <th>AI Score</th>
                    <th>Date Added</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>
                <tbody id="infinite-scroll-container">
                @forelse($patients as $patient)
                    <tr class="patient-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-primary">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $patient->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted fw-medium">{{ $patient->age }} <span class="mx-1">•</span> {{ $patient->gender }}</td>
                        <td>
                            @if($patient->condition)
                                <span class="fw-semibold text-dark">{{ $patient->condition }}</span>
                            @else
                                <span class="text-muted small fst-italic">Pending...</span>
                            @endif
                        </td>
                        <td>
                                <span class="glass-badge badge-{{ $patient->risk_level ?? 'None' }}">
                                    {{ $patient->risk_level ?? 'Unanalyzed' }}
                                </span>
                        </td>
                        <td class="fw-bold font-monospace text-dark">
                            {{ $patient->risk_score ? number_format($patient->risk_score, 2) : '-' }}
                        </td>
                        <td class="text-muted small">
                            {{ $patient->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <a href="/analyze/{{ $patient->id }}" class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-shadow">
                                Analyze <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            No patients found. Click "Add Patient" to begin.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="d-none" id="pagination-links">
                @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $patients->links() }}
                @endif
            </div>

            <div class="page-load-status text-center mt-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="scroll-sentinel"></div>

        </div>
    </div>

</div>

<script>
    // 1. Disease Bar Chart
    const ctxDisease = document.getElementById('diseaseChart').getContext('2d');
    new Chart(ctxDisease, {
        type: 'bar',
        data: {
            labels: @json($chart_labels),
            datasets: [{
                label: 'Cases',
                data: @json($chart_values),
                backgroundColor: 'rgba(102, 126, 234, 0.7)',
                borderRadius: 6,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // 2. Risk Doughnut Chart
    const ctxRisk = document.getElementById('riskChart').getContext('2d');
    new Chart(ctxRisk, {
        type: 'doughnut',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                data: [{{ $stats['high_risk'] }}, {{ $stats['medium_risk'] }}, {{ $stats['low_risk'] }}],
                backgroundColor: ['#dc3545', '#ffc107', '#198754'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });

    // 3. INFINITE SCROLL LOGIC (Vanilla JS)
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('infinite-scroll-container');
        const sentinel = document.getElementById('scroll-sentinel');
        const status = document.querySelector('.page-load-status');
        let nextPageUrl = null;
        let isLoading = false;

        // Try to find the initial next page URL from the hidden pagination
        const paginationLinks = document.getElementById('pagination-links');
        if (paginationLinks) {
            // Find the active page, then look for the next link
            const activePage = paginationLinks.querySelector('.active');
            if (activePage) {
                // The next <li> after active usually contains the next link
                const nextLi = activePage.closest('li').nextElementSibling;
                const nextLink = nextLi ? nextLi.querySelector('a') : null;
                if (nextLink) nextPageUrl = nextLink.href;
            } else {
                // Fallback: just look for a "next" rel or simple link logic if Bootstrap stricture varies
                const nextLink = paginationLinks.querySelector('a[rel="next"]');
                if (nextLink) nextPageUrl = nextLink.href;
            }
        }

        const observer = new IntersectionObserver(async (entries) => {
            if (entries[0].isIntersecting && nextPageUrl && !isLoading) {
                isLoading = true;
                status.style.display = 'block';

                try {
                    const response = await fetch(nextPageUrl);
                    const html = await response.text();

                    // Parse the HTML to extract rows and new pagination
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Append New Rows
                    const newRows = doc.querySelectorAll('#infinite-scroll-container tr.patient-row');
                    newRows.forEach(row => container.appendChild(row));

                    // 2. Find Next URL for subsequent scrolls
                    const newPagination = doc.getElementById('pagination-links');
                    if (newPagination) {
                        const nextLink = newPagination.querySelector('a[rel="next"]');
                        nextPageUrl = nextLink ? nextLink.href : null;
                    } else {
                        nextPageUrl = null;
                    }

                } catch (error) {
                    console.error('Error loading more patients:', error);
                } finally {
                    isLoading = false;
                    status.style.display = 'none';
                    if (!nextPageUrl) {
                        observer.unobserve(sentinel); // Stop observing if no more pages
                        sentinel.remove();
                    }
                }
            }
        }, { rootMargin: '200px' });

        if(sentinel && nextPageUrl) {
            observer.observe(sentinel);
        }
    });
</script>

</body>
</html>
