<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediAI - Smart Clinical Triage & Diagnostics</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
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
            display: flex;
            flex-direction: column;
        }

        /* Animated Background Blobs */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px); z-index: -1;
        }
        body::before { background: rgba(102, 126, 234, 0.4); width: 500px; height: 500px; top: -10%; left: -10%; }
        body::after { background: rgba(118, 75, 162, 0.4); width: 400px; height: 400px; bottom: -5%; right: -5%; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding: 1rem 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            padding: 3rem;
            transition: transform 0.3s ease;
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-outline-custom {
            background: rgba(255,255,255,0.5);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline-custom:hover {
            background: white;
            color: #667eea;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<nav class="glass-nav sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                <i class="bi bi-hospital fs-4"></i>
            </div>
            <h4 class="mb-0 fw-bold text-dark">MediAI</h4>
        </div>

        <div class="d-flex gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary-custom py-2 px-4">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-custom py-2 px-4">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary-custom py-2 px-4 d-none d-md-inline-block">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>

<div class="container flex-grow-1 d-flex align-items-center pt-5 pb-5">
    <div class="row w-100 align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm mb-3 border border-light">
                    <i class="bi bi-robot me-1"></i> Pragyan 2026 Hackathon
                </span>
            <h1 class="display-4 fw-bold mb-4">
                Smart Clinical <br>
                <span class="text-gradient">Triage & Diagnostics</span>
            </h1>
            <p class="lead text-muted mb-5 fs-6 lh-lg pe-lg-5">
                Empowering healthcare professionals with AI-driven patient prioritization, vital sign analysis, and rapid differential diagnosis to reduce wait times and save lives.
            </p>
            <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary-custom">Go to Dashboard <i class="bi bi-arrow-right ms-2"></i></a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary-custom">Get Started <i class="bi bi-arrow-right ms-2"></i></a>
                @endauth
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="glass-card h-100 p-4">
                        <div class="feature-icon text-primary"><i class="bi bi-activity"></i></div>
                        <h5 class="fw-bold">Vitals Analysis</h5>
                        <p class="text-muted small mb-0">Machine learning models instantly evaluate patient vitals to determine urgency and risk levels.</p>
                    </div>
                </div>
                <div class="col-md-6 mt-md-5">
                    <div class="glass-card h-100 p-4">
                        <div class="feature-icon text-danger"><i class="bi bi-sort-down-alt"></i></div>
                        <h5 class="fw-bold">Smart Triage</h5>
                        <p class="text-muted small mb-0">Automated queue sorting ensures the most critical patients are attended to first.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card h-100 p-4">
                        <div class="feature-icon text-success"><i class="bi bi-search"></i></div>
                        <h5 class="fw-bold">Differential Diagnosis</h5>
                        <p class="text-muted small mb-0">Input patient symptoms to receive explainable AI-backed condition predictions.</p>
                    </div>
                </div>
                <div class="col-md-6 mt-md-5">
                    <div class="glass-card h-100 p-4">
                        <div class="feature-icon text-warning"><i class="bi bi-hospital"></i></div>
                        <h5 class="fw-bold">Department Routing</h5>
                        <p class="text-muted small mb-0">Automatically route patients to Cardiology, Neurology, or Emergency based on AI insights.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
