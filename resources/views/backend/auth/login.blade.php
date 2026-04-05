<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Secure Login - Loan Management System">
    <meta name="author" content="Loan Management">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend_assets/assets/images/company_logo.png') }}">
    <title>Secure Login | Loan Management System</title>
    
    <!-- Custom Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('backend_assets/dist/css/style.min.css') }}">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 48px;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo img {
            width: 180px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h2 {
            font-weight: 700;
            font-size: 1.875rem;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 8px;
            display: block;
            color: #475569;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .login-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
            color: #64748b;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            accent-color: var(--primary-color);
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s active;
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .login-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 0.875rem;
            color: #64748b;
        }

        .login-footer a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #dcfce7;
        }

        /* Loading Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="preloader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="login-card">
        <div class="brand-logo">
            <img src="{{ asset('backend_assets/assets/images/company_logo.png') }}" alt="Logo">
        </div>
        
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your account</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" id="username" name="username" type="text"
                    placeholder="Enter your username" 
                    value="{{ old('username', request()->cookie('remember_username')) }}" 
                    required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password"
                    placeholder="Enter your password" required>
            </div>

            <div class="login-extras">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember" 
                        {{ old('remember', request()->hasCookie('remember_username')) ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                <a href="{{ route('login.forgot_password') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login shadow-sm">Sign In</button>
        </form>

        <div class="login-footer">
            Don't have an account? <a href="{{ route('logup.index') }}">Sign Up</a>
        </div>
    </div>

    <script src="{{ asset('backend_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(window).on('load', function() {
            $(".preloader").fadeOut(400);
        });
    </script>
</body>

</html>