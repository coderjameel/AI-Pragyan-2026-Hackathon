<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient</title>

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
            align-items: center;
            justify-content: center;
        }

        /* Abstract Background Blobs */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px); z-index: -1;
        }
        body::before { background: rgba(102, 126, 234, 0.4); width: 400px; height: 400px; top: -10%; left: -10%; }
        body::after { background: rgba(118, 75, 162, 0.4); width: 300px; height: 300px; bottom: -5%; right: -5%; }

        /* Glass Card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        /* Form Inputs */
        .form-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #718096; margin-bottom: 0.5rem; }

        .input-group-text {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-right: none;
            color: #667eea;
            border-radius: 12px 0 0 12px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 12px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            background: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* Button */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .back-link {
            position: absolute; top: 20px; left: 20px;
            color: #718096; text-decoration: none; font-size: 0.9rem;
            display: flex; align-items: center; transition: color 0.2s;
        }
        .back-link:hover { color: #2d3748; }
    </style>
</head>
<body>

<div class="container">
    <div class="glass-card mx-auto">

        <a href="{{ route('dashboard') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>

        <div class="text-center mb-5 mt-3">
            <div class="bg-white p-3 rounded-circle shadow-sm d-inline-block mb-3">
                <i class="bi bi-person-plus-fill fs-2 text-primary"></i>
            </div>
            <h3 class="fw-bold text-dark">New Patient</h3>
            <p class="text-muted small">Enter patient details to begin assessment</p>
        </div>

        <form action="/add-patient" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required autofocus>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label">Age</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="number" name="age" class="form-control" placeholder="Years" min="0" max="120" required>
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                        <select name="gender" class="form-select" required>
                            <option value="" selected disabled>Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Create Patient Profile
            </button>

        </form>
    </div>
</div>

</body>
</html>
