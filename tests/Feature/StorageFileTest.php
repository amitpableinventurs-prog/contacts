<?php

use Illuminate\Support\Facades\Storage;

it('serves an existing file from the public disk via /storage route', function () {
    Storage::disk('public')->put('contacts/test-avatar.png', base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
    ));

    $response = $this->get('/storage/contacts/test-avatar.png');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');
});

it('returns 404 for missing files via /storage route', function () {
    $this->get('/storage/contacts/does-not-exist.png')->assertNotFound();
});

it('blocks path traversal attempts via /storage route', function () {
    $this->get('/storage/../../.env')->assertNotFound();
});

