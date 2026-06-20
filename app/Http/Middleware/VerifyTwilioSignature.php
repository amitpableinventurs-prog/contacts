<?php

namespace App\Http\Middleware;

use App\Settings\TwilioSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Security\RequestValidator;

class VerifyTwilioSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = app(TwilioSettings::class);
        $token = $settings->token ?: config('twilio.token');

        // No token configured → we're in fake/dev mode. Skip validation.
        if (! $token) {
            return $next($request);
        }

        $signature = $request->header('X-Twilio-Signature', '');
        if (! $signature) {
            Log::warning('Twilio webhook missing signature', ['url' => $request->fullUrl()]);
            abort(403, 'Missing Twilio signature.');
        }

        $validator = new RequestValidator($token);
        $valid = $validator->validate(
            $signature,
            $request->fullUrl(),
            $request->post(),
        );

        if (! $valid) {
            Log::warning('Twilio webhook signature failed validation', [
                'url' => $request->fullUrl(),
                'signature' => $signature,
            ]);
            abort(403, 'Invalid Twilio signature.');
        }

        return $next($request);
    }
}
