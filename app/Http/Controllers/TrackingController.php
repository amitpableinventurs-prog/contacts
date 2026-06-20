<?php

namespace App\Http\Controllers;

use App\Models\EmailMessage;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    /**
     * 1x1 transparent GIF; counts opens via the tracking pixel embedded in
     * the contact email template at resources/views/emails/contact.blade.php.
     */
    public function emailPixel(string $trackingId): Response
    {
        $email = EmailMessage::withoutTeamScope()
            ->where('tracking_id', $trackingId)
            ->first();

        if ($email) {
            $now = now();
            $email->forceFill([
                'opens_count' => $email->opens_count + 1,
                'first_opened_at' => $email->first_opened_at ?? $now,
                'last_opened_at' => $now,
            ])->save();
        }

        // Smallest valid 1x1 transparent GIF (43 bytes).
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => (string) strlen($gif),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
