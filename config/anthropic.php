<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Anthropic API
    |--------------------------------------------------------------------------
    | When `fake` is true, structured-output calls return canned data
    | (good for dev). Defaults to true if no API key is set.
    */
    'fake' => env('ANTHROPIC_FAKE', empty(env('ANTHROPIC_API_KEY'))),

    'api_key' => env('ANTHROPIC_API_KEY'),
    'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com/v1'),
    'model' => env('ANTHROPIC_MODEL', 'claude-opus-4-7'),
    'max_tokens' => 1024,
];
