<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio fake mode
    |--------------------------------------------------------------------------
    |
    | When `fake` is true, SMS/voice operations are simulated:
    | messages are persisted with a generated SID and "queued" status,
    | but no API call is made. Defaults to true unless TWILIO_SID is set.
    |
    */
    'fake' => env('TWILIO_FAKE', env('TWILIO_SID') === null),

    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'from' => env('TWILIO_NUMBER'),

    'api_key' => env('TWILIO_API_KEY'),
    'api_secret' => env('TWILIO_API_SECRET'),
    'application_sid' => env('TWILIO_APPLICATION_SID'),
];
