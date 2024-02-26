<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //      $user = auth()->user();
    //     if (auth()->check() && $user->two_factor_code) {
    //         if ($user->two_factor_expires_at < now()) {
    //             $user->resetTwoFactorCode();
    //             auth()->logout();
    //             return redirect()->route('login')
    //                 ->withStatus('Your verification code expired. Please re-login.');
    //         }
    //         if (!$request->is('verify*')) {
    //             return redirect()->route('verify.index');
    //         }
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        // $user = auth()->user();
        // if (auth()->check() && $user->two_factor_code) {
        //     if ($user->two_factor_expires_at < now()) {
        //         $user->resetTwoFactorCode();
        //         auth()->logout();
        //         return redirect()->route('login')
        //             ->withStatus('Your verification code expired. Please re-login.');
        //     }
        //     if (!$request->is('verify*')) {
        //         return redirect()->route('verify.index');
        //     }
        // }
         $user = auth()->user();

        if (auth()->check() && $user->verification_code) {
            if ($user->verification_code_expires_at < now()) {
                $user->resetTwoFactorCode();
                auth()->logout();

                return response()->json(['error' => 'Your verification code expired. Please re-login.'], 401);
            }

            if (!$request->is('api/v1/verify*')) {
                return response()->json(['error' => 'Two-factor authentication required.'], 403);
            }
        }
        return $next($request);
    }
}
