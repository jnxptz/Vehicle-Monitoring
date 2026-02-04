<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <img src="{{ asset('images/SP Seal.png') }}" alt="Logo" class="auth-logo">
        <h2>Forgot Password</h2>

        @if(session('status'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        

        <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 20px;">
            Enter your email address and we'll send you instructions to reset your password.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div style="color: #d32f2f; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror

            <button type="submit" style="margin-top: 20px;">Send Reset Link</button>
        </form>

        <div class="link">
            <a href="{{ route('login.form') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
