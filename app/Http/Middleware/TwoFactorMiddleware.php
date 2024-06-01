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
    public function handle(Request $request, Closure $next): Response
    {


        /** @var \App\Models\User $user **/
        $user = auth()->user();
        if (auth()->check() && $user->two_factor_code) {
            if ($user->two_factor_expires_at < now()) {

                auth('web')->logout();
                $request->session()->invalidate();
                $user->resetTwoFactorCode();
                return response()->json(['message' => 'Your verification code expired. Please re-login.', 'code' => 'expired'], 401);
            }
            if (!$request->is('api/v1/verify*')) {
                return
                    response()->json(['message' => 'Verification code required', 'code' => 'required'], 401);
            }
        }

        return $next($request);
    }
}
