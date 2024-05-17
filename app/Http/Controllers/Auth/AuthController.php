<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Events\UserLoggedIn;
use Illuminate\Http\Request;
use App\Events\UserLoginAttempt;
use App\Models\VerificationCode;
use App\Events\FailedLoginAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\SendTwoFactorCode;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|integer',
        ]);

        // Find the verification code
        $verificationCode = VerificationCode::where('user_id', auth()->user()->id)
            ->where('code', $request->code)
            ->first();

        if (!is_null($verificationCode)) {
            if ($verificationCode->expires_at >= now()) {
                // Mark the verification code as used
                $verificationCode->timestamps = false;

                // Mark the verification code as used
                $verificationCode->used_at = now(); // Update the used_at field
                $verificationCode->save();

                // Set the user_2fa session
                session(['user_2fa' => auth()->user()->id]);

                return response()->json(['success' => true, 'message' => 'Login Successful!'], 200);
            } else {
                // Prepare error response for expired code
                $errors = ['code' => ['Verification code expired']];
                return response()->json(['success' => false, 'message' => 'Verification code expired', 'errors' => $errors], 422);
            }
        } else {
            // Prepare error response for invalid code
            $errors = ['code' => ['Invalid Code']];
            return response()->json(['success' => false, 'message' => 'Invalid Code', 'errors' => $errors], 422);
        }
    }


    public function resend()
    {
         /** @var \App\Models\User $user **/
        $user = auth()->user();
        // Generate a new verification code and store it
        $verificationCode = $user->generateVerificationCode();

        // Send the verification email with the stored verification code
        $mail = $user->notify(new SendTwoFactorCode($verificationCode));
        return response()->json(['success' => true, 'message' =>'Verification Code Sent Again!', 'data'=>$mail], 200);
        // $mail = auth()->user()->sendVerificationEmail();
        // return response()->json(['success' => $mail['success'], 'message' => $mail['message']], 200);
    }

    public function signIn(LoginRequest $request)
    {
        // Validate user credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (!auth()->attempt($credentials)) {
            event(new FailedLoginAttempt($credentials['email'], $request->ip()));
            return response()->json([
                'message' => 'These credentials do not match our records.',
                'errors' => [
                    'email' => ['These credentials do not match our records.']
                ]
            ], 422);
        }
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        event(new UserLoginAttempt($user));
        // Dispatch the UserLoginAttempt event with the email address and success indication
        // event(new UserLoginAttempt($credentials['email'], true));

        return response()->json(['success' => true, 'message' => 'Verification Code Sent'], 200);
    }
}
