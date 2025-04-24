<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogins
{
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 10): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                "message" => "Too many attempts. Please try again in " . ceil($seconds / 60) . " minutes.",
                "retry_after" => $seconds
            ], 429);
        }

        $response = $next($request);

        // Check route name for system complaints first
        if ($request->route()->named('system-complaints.store')) {
            // Always increment counter for all system complaints submissions regardless of status
            RateLimiter::hit($key, 60 * $decayMinutes);
        }
        // Only increment the limiter on successful registration (not on validation failures)
        elseif ($request->route()->named('auth.register') && $response->getStatusCode() === 200) {
            RateLimiter::hit($key, 60 * $decayMinutes);
        } elseif ($request->route()->named('auth.resend-otp') && $response->getStatusCode() === 200) {
            // Increment counter even for successful OTP resend
            RateLimiter::hit($key, 60 * $decayMinutes);
        } elseif (($request->route()->named('auth.forgot-password') || $request->route()->named('auth.forgot-password-mobile')) && $response->getStatusCode() === 200) {
            RateLimiter::hit($key, 60 * $decayMinutes); 
        } elseif ($response->getStatusCode() !== 200) {
            // Increment for failed login/refresh/resend-otp attempts
            if (!$request->route()->named('auth.register')) {
                RateLimiter::hit($key, 60 * $decayMinutes);
            }
        }

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);

        return $response;
    }

    protected function resolveRequestSignature(Request $request): string
    {
        if ($request->route()->named('system-complaints.store')) {
            // For system complaints, use user's IP address
            return 'system_complaints:' . $request->ip();
        }

        if ($request->route()->named('auth.register')) {
            // For registration, throttle based on IP only
            return 'register_ip:' . $request->ip();
        }

        if ($request->route()->named('auth.login')) {
            // For login, use email + IP
            return Str::lower($request->input('email') . '|' . $request->ip());
        }

        if ($request->route()->named('auth.dashboard.login')) {
            // For dashboard login, use email + IP with a prefix to distinguish it
            return 'dashboard:' . Str::lower($request->input('email') . '|' . $request->ip());
        }

        if ($request->route()->named('auth.refresh')) {
            // For token refresh, use refresh token + IP
            $token = $request->cookie('refresh_token') ?? 'no-token';
            return 'refresh:' . $token . '|' . $request->ip();
        }

        if ($request->route()->named('auth.resend-otp')) {
            // For OTP resend, use email/phone + IP
            $identifier = $request->input('email') ?? $request->input('phone') ?? 'unknown';
            return 'resend_otp:' . Str::lower($identifier) . '|' . $request->ip();
        }

        if ($request->route()->named('auth.forgot-password') || $request->route()->named('auth.forgot-password-mobile')) {
            $identifier = $request->input('email') ?? $request->ip();
            return 'forgot_password:' . Str::lower($identifier) . '|' . $request->ip();
        }

        // Fallback to IP only
        return $request->ip();
    }
}

