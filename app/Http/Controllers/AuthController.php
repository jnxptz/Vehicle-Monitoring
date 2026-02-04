<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'boardmember') {
                return redirect()->route('boardmember.dashboard');
            }

            
            Auth::logout();
            return redirect()->route('login.form')->with('error', 'Your account role is invalid.');
        }

        return back()->with('error', 'Invalid email or password!');
    }

    
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'boardmember', // default role for registration
        ]);

        Auth::login($user);

        return redirect()->route('boardmember.dashboard');
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }

    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    
    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Get user by email
        $user = User::where('email', $request->email)->first();

        // Generate a unique token
        $token = Str::random(64);

        // Store the token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Create the reset URL
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Send email with reset link
        try {
            Mail::send(new ResetPasswordMail($resetUrl, $user->email, $user->name));
            $emailSent = true;
        } catch (\Exception $e) {
            // In development, show error details
            if (config('app.debug')) {
                return back()->with('error', 'Email Error: ' . $e->getMessage());
            }
            $emailSent = false;
        }

        // In development mode, also show the reset link as fallback
        if (config('app.debug')) {
            return back()->with('status', 'Password reset instructions have been sent to ' . $request->email)
                        ->with('resetUrl', $resetUrl)
                        ->with('email', $request->email);
        }

        return back()->with('status', 'If an account exists with that email, you will receive a password reset link.');
    }

    
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if token exists and is valid
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Invalid or expired password reset token.');
        }

        // Check if token is not older than 1 hour
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Password reset link has expired. Please request a new one.');
        }

        // Update the user's password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login.form')->with('status', 'Your password has been reset successfully. Please log in with your new password.');
    }
}
