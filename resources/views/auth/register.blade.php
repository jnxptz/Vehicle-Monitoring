<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
                <label>Name</label>
                <input type="text" name="name" required>

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>

                <button type="submit">Register</button>
            </form>

            <div class="link">
                Already have an account?
                <a href="{{ route('login.form') }}">Login here</a>
            </div>
        </div>

        <!-- RIGHT: LOGO -->
        <div class="auth-logo-right">
            <img src="{{ asset('images/vmbslogo.png') }}" class="auth-logo" alt="Logo">
        </div>
    </div>
</body>
</html>
