<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Create Account - Loan Management System">
    <meta name="author" content="Loan Management">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend_assets/assets/images/company_logo.png') }}">
    <title>ចុះឈ្មោះ | Create Your Account</title>
    
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
            padding: 20px;
        }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 550px;
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-logo img {
            width: 150px;
            height: auto;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h2 {
            font-weight: 700;
            font-size: 1.75rem;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .auth-header p {
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
            font-size: 0.95rem;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
            background-color: #fcfdfe;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background-color: #ffffff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.875rem;
            color: #64748b;
        }

        .auth-footer a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
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

        .preloader {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 20px;
                border-radius: 16px;
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

    <div class="auth-card">
        <div class="brand-logo">
            <img src="{{ asset('backend_assets/assets/images/company_logo.png') }}" alt="Logo">
        </div>
        
        <div class="auth-header">
            <h2>បង្កើតគណនីថ្មី</h2>
            <p>Join our system for secure financial management</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('logup.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 pr-md-2">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input class="form-control" id="name" name="name" type="text"
                            placeholder="Your Name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-6 pl-md-2">
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username" type="text"
                            placeholder="Unique ID" value="{{ old('username') }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-control" id="email" name="email" type="email"
                    placeholder="example@mail.com" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password"
                    placeholder="At least 8 characters" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password"
                    placeholder="Verify your password" required>
            </div>

            <button type="submit" class="btn-submit shadow-sm">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login.index') }}">Sign In</a>
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