<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\SendTwoFactorCode;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {

        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        // $code = $request->user()->generateTwoFactorCode();
        // $request->user()->notify(new SendTwoFactorCode());
        return response(['success' => true,
            'data' =>[
                'token' => $user->createToken($user->name)->plainTextToken,
                'name' => $user->name,
            ],
            'message'=> 'User logged in!'
        ]);

        // return response()->noContent();
        // return response(['error' => 'Your verification code expired. Please re-login.', 'code' => $code ], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
