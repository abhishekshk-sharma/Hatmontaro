<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send email
        $resetUrl = url('/admin/password/reset/'.$token.'?email='.urlencode($request->email));

        try {
            Mail::send('emails.admin-password-reset', [
                'resetUrl' => $resetUrl,
                'email' => $request->email,
            ], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Admin Password Reset Request');
            });

            return back()->with('status', 'Password reset link sent to your email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send reset email. Please try again.']);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $passwordReset || ! Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        // Update admin password
        $admin = Admin::where('email', $request->email)->first();
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', 'Password has been reset successfully!');
    }
}
