<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Events\UserLoggedIn;
use Illuminate\Http\Request;
use App\Events\UserLoginAttempt;
use App\Models\VerificationCode;
use Illuminate\Http\JsonResponse;
use App\Events\FailedLoginAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Validation\ValidationException;


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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        if ($request->input('two_factor_code') !== $user->two_factor_code) {
            throw ValidationException::withMessages([
                'two_factor_code' => __('The code you entered doesn\'t match our records'),
                'asd' => $user->two_factor_code
            ]);
            // $errors = ['code' => ['The code you entered doesn\'t match our records']];
            // return response()->json(['success' => false, 'message' => 'The code you entered doesn\'t match our records', 'errors' => $errors], 422);
        }
        $user->resetTwoFactorCode();
        return response()->json(['success' => true, 'message' => 'Login Successful!'], 200);
    }

    /**
     * Resend the two-factor authentication code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(): JsonResponse
    {

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        return
            response()->json([
                'success' => true,
                'message' => 'A new two-factor authentication code has been sent.',
            ], 200);
    }
    // public function resend()
    // {
    //     /** @var \App\Models\User $user **/
    //     $user = auth()->user();
    //     // Generate a new verification code and store it
    //     $verificationCode = $user->generateVerificationCode();

    //     // Send the verification email with the stored verification code
    //     $mail = $user->notify(new SendTwoFactorCode($verificationCode));
    //     return response()->json(['success' => true, 'message' => 'Verification Code Sent Again!', 'data' => $mail], 200);
    //     // $mail = auth()->user()->sendVerificationEmail();
    //     // return response()->json(['success' => $mail['success'], 'message' => $mail['message']], 200);
    // }

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
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        event(new UserLoginAttempt($user));

        return response()->json(['success' => true, 'message' => 'Verification Code Sent'], 200);
    }
}
