<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <img src="{{ asset('images/SP Seal.png') }}" alt="Logo" class="auth-logo">
        <h2>Reset Password</h2>

        @if($errors->any())
            <div class="error" style="margin-bottom: 15px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            @error('email')
                <div style="color: #d32f2f; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror

            <label style="margin-top: 15px;">New Password</label>
            <input type="password" name="password" placeholder="Enter new password" required>
            @error('password')
                <div style="color: #d32f2f; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror

            <label style="margin-top: 15px;">Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>
            @error('password_confirmation')
                <div style="color: #d32f2f; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror

            <button type="submit" style="margin-top: 20px;">Reset Password</button>
        </form>

        <div class="link">
            <a href="{{ route('login.form') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
