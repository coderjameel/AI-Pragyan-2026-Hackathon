<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediAI - Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            animation: pulse 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
            animation: pulse 10s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3), 0 0 40px rgba(99, 102, 241, 0.1); }
            50% { box-shadow: 0 0 30px rgba(99, 102, 241, 0.5), 0 0 60px rgba(99, 102, 241, 0.2); }
        }

        .auth-container {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            padding: 48px 40px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 1;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            position: relative;
            animation: glow 3s ease-in-out infinite;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 20px;
            z-index: -1;
            opacity: 0.5;
            filter: blur(10px);
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        .auth-title {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            color: #94a3b8;
            font-size: 15px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 10px;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            font-size: 15px;
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Inter', sans-serif;
        }

        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #64748b;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            background: rgba(30, 41, 59, 0.7);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .error-message {
            color: #f87171;
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
        }

        .status-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #6366f1;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 400;
            font-size: 14px;
            cursor: pointer;
            color: #cbd5e1;
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .auth-links a {
            color: #a5b4fc;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            transition: width 0.3s ease;
        }

        .auth-links a:hover {
            color: #c7d2fe;
        }

        .auth-links a:hover::after {
            width: 100%;
        }

        .divider {
            text-align: center;
            margin: 32px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.2), transparent);
        }

        .divider span {
            background: rgba(17, 24, 39, 0.9);
            padding: 0 16px;
            position: relative;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .toggle-auth {
            text-align: center;
            margin-top: 32px;
            font-size: 15px;
            color: #94a3b8;
        }

        .toggle-auth a {
            color: #a5b4fc;
            font-weight: 700;
            text-decoration: none;
            margin-left: 6px;
            position: relative;
            transition: color 0.3s ease;
        }

        .toggle-auth a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .toggle-auth a:hover {
            color: #c7d2fe;
        }

        .toggle-auth a:hover::after {
            transform: scaleX(1);
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 36px 24px;
            }

            .auth-title {
                font-size: 28px;
            }

            .logo-icon {
                width: 70px;
                height: 70px;
            }

            .logo-icon i {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="logo-section">
        <div class="logo-icon">
            <i class="bi bi-hospital"></i>
        </div>
        <h1 class="auth-title">
            @if(request()->routeIs('register'))
                Create Account
            @elseif(request()->routeIs('password.request'))
                Reset Password
            @else
                Welcome Back
            @endif
        </h1>
        <p class="auth-subtitle">
            @if(request()->routeIs('register'))
                Join MediAI for smarter healthcare
            @elseif(request()->routeIs('password.request'))
                Enter your email to reset password
            @else
                Sign in to access your dashboard
            @endif
        </p>
    </div>

    @if(session('status'))
        <div class="status-message">
            {{ session('status') }}
        </div>
    @endif

    @if(request()->routeIs('register'))
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Create Account</button>

            <div class="toggle-auth">
                Already have an account?<a href="{{ route('login') }}">Sign In</a>
            </div>
        </form>
    @elseif(request()->routeIs('password.request'))
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Send Reset Link</button>

            <div class="toggle-auth">
                Remember your password?<a href="{{ route('login') }}">Sign In</a>
            </div>
        </form>
    @else
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-primary">Sign In</button>

            <div class="auth-links">
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
                @if(Route::has('register'))
                    <a href="{{ route('register') }}">Create account</a>
                @endif
            </div>
        </form>
    @endif
</div>
</body>
</html>
