<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Vehicle Monitoring System</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <img src="{{ asset('images/SP Seal.png') }}" alt="Logo" class="auth-logo" style="display: block; height: 120px; width: auto; margin: 0 auto 20px; object-fit: contain;">
        <h2>Forgot Password</h2>

        @if(session('status'))
            <div class="success-message" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: 500; border: 1px solid #a7f3d0; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; padding: 16px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: 500; border: 1px solid #fecaca; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1);">{{ session('error') }}</div>
        @endif

        <p style="text-align: center; color: #64748b; font-size: 15px; margin-bottom: 24px; line-height: 1.5;">
            Enter your email address and we'll send you instructions to reset your password.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email address">
            @error('email')
                <div class="field-error" style="color: #dc2626; font-size: 13px; margin-top: 6px; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color: #dc2626;">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" style="margin-top: 24px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                Send Reset Link
            </button>
        </form>

        <div class="link" style="margin-top: 24px;">
            <a href="{{ route('login.form') }}" style="color: #3b82f6; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>
