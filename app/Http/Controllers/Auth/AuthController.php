<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Information; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('loginpage');
    }

        public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // ✅ Use rate limiter (max 3 attempts per 3 minutes)
            $key = Str::lower($request->email) . '|' . $request->ip();
            $maxAttempts = 3;
            $decayMinutes = 3;

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = ceil($seconds / 60);
                return back()->withErrors([
                    'email' => "Too many login attempts. Please try again in {$minutes} minute(s)."
                ]);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                RateLimiter::hit($key, $decayMinutes * 60); // increment failed attempt
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->withInput();
            }

            // ✅ Reset limiter after successful login
            RateLimiter::clear($key);

            $request->session()->regenerate();

            // Get email domain
            $emailDomain = substr(strrchr($request->email, "@"), 1);

            // Redirect based on email domain
            if ($emailDomain === 'admin.com') {
                return redirect()->route('admin.dashboard')->with('success', Auth::user()->name . ' Login successful!');
            } elseif ($emailDomain === 'staff.com') {
                return redirect()->route('staff.dashboard')->with('success', Auth::user()->name . ' Login successful!');
            } else {
                return redirect()->route('customer.dashboard')->with('success', Auth::user()->name . ' Login successful!');
            }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',       // lowercase
                'regex:/[A-Z]/',       // uppercase
                'regex:/[0-9]/',       // number
                'regex:/[@$!%*#?&]/',  // special char
                'confirmed'
            ], 
            'contact_number' => 'required|string|unique:user_information,contact_number',
            ],
        [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ allow only Gmail or my.cspc.edu.ph
        $domain = substr(strrchr($request->email, "@"), 1);
        $allowed = ['gmail.com', 'my.cspc.edu.ph'];
        if (!in_array($domain, $allowed)) {
            return back()->withErrors(['email' => 'Only Gmail or my.cspc.edu.ph emails are allowed.'])->withInput();
        }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'is_verified' => false, // we'll use this for phone verification
            ]);

          // Create user_information with phone verification
                    $phoneCode = rand(100000, 999999);

                    $userInfo = Information::create([
                        'user_id' => $user->id,
                        'fullname' => $request->name,
                        'contact_number' => $request->contact_number,
                        'phone_verification_code' => $phoneCode,
                        'phone_verified' => false,
                    ]);


                // Create user_information with phone verification
            $phoneCode = rand(100000, 999999);

            $userInfo = Information::create([
                'user_id' => $user->id,
                'fullname' => $request->name,
                'contact_number' => $request->contact_number,
                'phone_verification_code' => $phoneCode,
                'phone_verified' => false,
            ]);

          

            // Clean Philippine number
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->contact_number);

            // Convert 09xxxxx → +639xxxx
            if (substr($cleanPhone, 0, 2) === '09') {
                $cleanPhone = '+63' . substr($cleanPhone, 1);
            } elseif (substr($cleanPhone, 0, 1) !== '+') {
                $cleanPhone = '+' . $cleanPhone;
            }

            // Encode parameters
            $phoneParam = urlencode($cleanPhone);
            $messageParam = urlencode("Your verification code is: $phoneCode");

            // Prepare URL
            $serverUrl = "http://192.168.137.19:8080/sms?par1={$phoneParam}&par2={$messageParam}";

            // Logging
            \Log::info("Sending OTP via MacroDroid", [
                'url' => $serverUrl,
                'phone' => $cleanPhone,
                'otp' => $phoneCode,
            ]);

            try {
                $response = Http::get($serverUrl);

                \Log::info("MacroDroid Response", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'successful' => $response->successful(),
                ]);

            } catch (\Exception $e) {
                \Log::error("OTP sending failed: ".$e->getMessage());
            }

            // Redirect to OTP verification screen
            return redirect()
                ->route('phone.verify.show', ['user_id' => $user->id])
                ->with('success', 'A verification code has been sent to your phone.');
        }
    

//     public function register(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users',
//         'password' => [
//             'required',
//             'string',
//             'min:8',
//             'regex:/[a-z]/',       // lowercase
//             'regex:/[A-Z]/',       // uppercase
//             'regex:/[0-9]/',       // number
//             'regex:/[@$!%*#?&]/',  // special char
//             'confirmed'
//         ], 
//         'contact_number' => 'required|string|unique:user_information,contact_number',
//     ], [
//         'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
//     ]);

//     if ($validator->fails()) {
//         return back()->withErrors($validator)->withInput();
//     }

//     // Allow only Gmail or my.cspc.edu.ph
//     $domain = substr(strrchr($request->email, "@"), 1);
//     $allowed = ['gmail.com', 'my.cspc.edu.ph'];
//     if (!in_array($domain, $allowed)) {
//         return back()->withErrors(['email' => 'Only Gmail or my.cspc.edu.ph emails are allowed.'])->withInput();
//     }

//     // Create user
//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//         'role' => 'customer',
//         'is_verified' => false,
//     ]);

//     // Create user_information with phone verification
//     $phoneCode = rand(100000, 999999);

//     $userInfo = Information::create([
//         'user_id' => $user->id,
//         'fullname' => $request->name,
//         'contact_number' => $request->contact_number,
//         'phone_verification_code' => $phoneCode,
//         'phone_verified' => false,
//     ]);

//     // Clean PH number
//     $cleanPhone = preg_replace('/[^0-9]/', '', $request->contact_number);
//     if (substr($cleanPhone, 0, 2) === '09') {
//         $cleanPhone = '63' . substr($cleanPhone, 1);
//     }

//     // Send OTP via PhilSMS
//     try {
//         $response = Http::withToken(env('PHILSMS_API_TOKEN'))
//             ->post(env('PHILSMS_URL') . '/sms/send', [
//                 'recipient' => $cleanPhone,
//                 'sender_id' => env('PHILSMS_SENDER'),
//                 'message'   => "Your verification code is: $phoneCode"
//             ]);

//         Log::info('PhilSMS OTP Response', [
//             'status' => $response->status(),
//             'body' => $response->body(),
//             'success' => $response->successful()
//         ]);

//     } catch (\Exception $e) {
//         Log::error("OTP sending failed: " . $e->getMessage());
//     }

//     // Redirect to OTP verification screen
//     return redirect()
//         ->route('phone.verify.show', ['user_id' => $user->id])
//         ->with('success', 'A verification code has been sent to your phone.');
// }

            public function showVerify(Request $request)
            {
                $user_id = $request->user_id;
                return view('verify-phone', compact('user_id'));
            }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_information,user_id',
            'code' => 'required|digits:6',
        ]);

        $userInfo = Information::where('user_id', $request->user_id)
                    ->where('phone_verification_code', $request->code)
                    ->first();

        if (!$userInfo) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $userInfo->phone_verified = true;
        $userInfo->phone_verification_code = null;
        $userInfo->save();

        return redirect()->route('login')->with('success', 'Registered  Successfully!');
    }

        public function resendCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_information,user_id'
        ]);

        $userInfo = Information::where('user_id', $request->user_id)->first();

        // New OTP
        $newCode = rand(100000, 999999);

        $userInfo->phone_verification_code = $newCode;
        $userInfo->save();

      
        // Clean phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $userInfo->contact_number);

        // Convert PH numbers
        if (substr($cleanPhone, 0, 2) === '09') {
            $cleanPhone = '+63' . substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 1) !== '+') {
            $cleanPhone = '+' . $cleanPhone;
        }

        // Encode parameters
        $phoneParam   = urlencode($cleanPhone);
        $messageParam = urlencode("Your verification code is: $newCode");

        // Create URL
        $serverUrl = "http://192.168.137.19:8080/sms?par1={$phoneParam}&par2={$messageParam}";

        \Log::info("Resending OTP via MacroDroid", [
            'url'   => $serverUrl,
            'phone' => $cleanPhone,
            'otp'   => $newCode,
        ]);

        try {
            $response = Http::get($serverUrl);

            \Log::info("MacroDroid OTP Resend Response", [
                'status'     => $response->status(),
                'body'       => $response->body(),
                'successful' => $response->successful(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Resend OTP failed: " . $e->getMessage());
        }

        return back()->with('success', 'A new verification code has been sent to your phone.');
    }


    // public function showVerify(Request $request)
    // {
    //     return view('verify');
    // }

    // public function verifyCode(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //         'code' => 'required|digits:6',
    //     ]);

    //     $user = User::where('email', $request->email)
    //                 ->where('verification_code', $request->code)
    //                 ->first();

    //     if (!$user) {
    //         return back()->withErrors(['code' => 'Invalid verification code.']);
    //     }

    //     $user->is_verified = true;
    //     $user->verification_code = null;
    //     $user->save();

    //     return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
    // }

    // ✅ resend verification code (normal form POST)
    // public function resendCode(Request $request)
    // {
    //     $request->validate(['email' => 'required|email|exists:users,email']);
    //     $user = User::where('email', $request->email)->first();

    //     $newCode = rand(100000, 999999);
    //     $user->verification_code = $newCode;
    //     $user->save();

    //     Mail::to($user->email)->send(new VerifyEmail($user, $newCode));

    //     return redirect()->route('verify.show', ['email' => $user->email])
    //         ->with('success', 'A new verification code has been sent to your email.');
    // }

       // ✅ Show Forgot Password Form
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    // ✅ Handle Forgot Password - Send Code
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = rand(100000, 999999);

        // Store code in session
        session([
            'reset_email' => $request->email,
            'reset_code' => $code
        ]);

        // Send the code by email
        Mail::raw("Your password reset code is: $code", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset Code');
        });

        return redirect()->route('verify.reset.code')
                         ->with('success', 'Verification code sent to your email.');
    }

    // ✅ Show Verify Code Form
    public function showVerifyResetCodeForm()
    {
        return view('verify-reset-code');
    }

    // ✅ Verify Code
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric'
        ]);

        if ($request->code != session('reset_code')) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        session(['code_verified' => true]);

        return redirect()->route('reset.password.form')
                         ->with('success', 'Code verified. You can now reset your password.');
    }

        public function resendResetCode(Request $request)
    {
        $email = session('reset_email'); // email stored during forgot password

        if (!$email) {
            return redirect()->route('forgot.password')->withErrors(['email' => 'No email found in session.']);
        }

        // Generate new 6-digit code
        $newCode = rand(100000, 999999);

        // Store new code and expiration in session (no DB)
        session([
            'reset_code' => $newCode,
            'reset_code_expires_at' => now()->addMinutes(2),
        ]);

        // Send email with new code
        try {
            \Mail::raw("Your new password reset code is: {$newCode}", function ($message) use ($email) {
                $message->to($email)->subject('Your New Password Reset Code');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email.']);
        }

        return back()->with('success', 'A new reset code has been sent to your email.');
    }

    // ✅ Show Reset Password Form
    public function showResetForm()
    {
        // Ensure user verified code first
        if (!session('code_verified')) {
            return redirect()->route('forgot.password')->withErrors(['error' => 'Please verify your code first.']);
        }

        return view('reset-password');
    }

    // ✅ Handle Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('forgot.password')->withErrors(['error' => 'Session expired. Try again.']);
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear session
        session()->forget(['reset_email', 'reset_code', 'code_verified']);

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now log in.');
    }



    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // Create a new user if not exists
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Random password for Google login
                    'role' => 'customer' // Default role for Google users
                ]);
            }

            Auth::login($user, true);
            return redirect()->route('customer.dashboard')->with('success', 'Login successful!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully');
    }

    // public function forgotPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users'
    //     ]);

    //     if ($validator->fails()) {
    //         return back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $status = Password::sendResetLink($request->only('email'));

    //     return back()->with('status', 'Password reset link has been sent to your email!');
    // }
}