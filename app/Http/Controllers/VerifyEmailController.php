<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    public function notice()
    {
        if (Auth::user()->email_verified_at) {
            return $this->redirectToDashboard();
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }

        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')
                ->with('error', 'This verification link has expired. Please request a new one.');
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        Auth::login($user);

        return $this->redirectToDashboard()->with('success', 'Your email has been verified successfully! Welcome.');
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return $this->redirectToDashboard();
        }

        static::sendVerificationEmail($user);

        return back()->with('resent', 'A new verification link has been sent to your email address.');
    }

    public static function sendVerificationEmail($user): void
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new EmailVerificationMail($url, $user->first_name ?? $user->name));
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();

        return match ($user->role) {
            'IT'        => redirect()->route('it.dashboard'),
            'CESO'      => redirect()->route('ceso.dashboard'),
            'Community' => redirect()->route('community.dashboard'),
            'Student'   => redirect()->route('student.dashboard'),
            'Faculty'   => redirect()->route('faculty.dashboard'),
            'Staff'     => redirect()->route('staff.dashboard'),
            default     => redirect('/'),
        };
    }
}
