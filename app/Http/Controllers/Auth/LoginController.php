<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout','showVerifyForm','verifyOtp']);
        $this->middleware('auth')->only('logout');
    }

    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Set OTP and expiry time
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP via email
        // Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.verify');
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = Auth::user();

        // if ($user->otp_code === $request->otp && $user->otp_expires_at->isFuture()) {
        if ($user->otp_code === $request->otp ) {
            // OTP is correct and not expired
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return redirect()->intended('/home');
        }

        return redirect()->route('otp.verify')->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     $otp = rand(100000, 999999); // Generate 6-digit OTP
    //     $user->otp_code = $otp;
    //     $user->otp_expires_at = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes
    //     $user->save();
    
    //     // Send OTP to user's email
    //     // Mail::to($user->email)->send(new SendOtpMail($otp));
    
    //     return redirect()->route('otp.verify');
    // }

}
