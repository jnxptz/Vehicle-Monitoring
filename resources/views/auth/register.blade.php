<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vehicle Monitoring System</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container horizontal">
        <!-- LEFT: FORM -->
        <div class="auth-form">
            <h2>Vehicle Monitoring System</h2>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" required>

                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required>

                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm your password" required>

                <button type="submit">Create Account</button>
            </form>

            <div class="link">
                Already have an account?
                <a href="{{ route('login.form') }}">Login here</a>
            </div>
        </div>

        <!-- RIGHT: LOGO -->
        <div class="auth-logo-right">
            <img src="{{ asset('images/carlogo.jpg') }}" class="auth-logo" alt="Logo">
        </div>
    </div>
</body>
</html>
