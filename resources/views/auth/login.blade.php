<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container horizontal">
        <!-- LEFT: FORM -->
        <div class="auth-form">
            <h2>Vehicle Monitoring System</h2>

            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <div class="auth-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>

                     <a href="{{ route('password.request') }}" class="forgot-link"> Forgot password?</a>
                </div>


                <button type="submit">Login</button>
            </form>

            <div class="link">
                Don't have an account?
                <a href="{{ route('register.form') }}">Register here</a>
            </div>
        </div>

        <!-- RIGHT: LOGO -->
        <div class="auth-logo-right">
            <img src="{{ asset('images/vmbslogo.png') }}" class="auth-logo" alt="Logo">
        </div>
    </div>
</body>
</html>
