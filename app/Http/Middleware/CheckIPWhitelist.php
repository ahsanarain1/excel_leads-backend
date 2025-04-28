<?php

namespace App\Http\Middleware;

use App\Events\FailedLoginAttempt;
use App\Models\IpWhitelist;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIPWhitelist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // Allow admin users to bypass IP check
    //     /** @var \App\Models\User $user **/
    //     $user = auth()->user();
    //     if ($user && $user->hasRole('admin')) {

    //         return $next($request);
    //     }
    //     // Get the user's IP address
    //     $userIp = $request->ip();

    //     // Check if the IP is in the whitelist
    //     $ipExists = IpWhitelist::where('ip_address', $userIp)->exists();

    //     // If the IP is not whitelisted, return a forbidden response along with the IP
    //     if (!$ipExists) {
    //         auth('web')->logout();
    //         return response()->json([
    //             'message' => 'Your IP ' . $userIp . ' is not recognizable. Please contact admin',
    //             'user_ip' => $userIp,  // Add the user's IP to the response
    //             'code' => 'invalid_ip'
    //         ], 403);
    //     }

    //     // Allow the request to proceed
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        // Skip IP check if IP is whitelisted
        $userIp = $request->ip();
        $ipExists = IpWhitelist::where('ip_address', $userIp)->exists();

        // If trying to log in
        if ($request->is('login')) {
            $email = $request->input('email');
            $user = \App\Models\User::where('email', $email)->first();

            // Allow if admin
            if ($user && $user->hasRole('admin')) {
                return $next($request);
            }

            // If IP not whitelisted
            if (!$ipExists) {
                event(new FailedLoginAttempt($user, $userIp));
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied from unrecognized IP address (' . $userIp . '). Please contact your administrator to request access.',
                    'user_ip' => $userIp,
                    'code' => 'invalid_ip'
                ], 403);
            }

            return $next($request);
        }

        // For already authenticated users
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return $next($request);
        }

        if (!$ipExists) {
            auth('web')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Access denied from unrecognized IP address (' . $userIp . '). Please contact your administrator to request access.',
                'user_ip' => $userIp,
                'code' => 'invalid_ip'
            ], 403);
        }

        return $next($request);
    }
}
