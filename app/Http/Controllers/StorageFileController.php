<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves files stored on the "public" disk through Laravel.
 *
 * On most setups Apache serves /storage/* straight from the public/storage
 * symlink. Some shared hosts disable symlink following, which makes those
 * requests fall through to the front controller — this route catches them
 * and streams the file from storage/app/public so DPs/logos never 404.
 *
 * It is a controller (not a closure) so it survives `route:cache`, which
 * is what cPanel deploys run.
 */
class StorageFileController extends Controller
{
    public function show(Request $request, string $path): Response
    {
        // Normalize and reject path traversal attempts.
        $path = str_replace('\\', '/', ltrim($path, '/'));

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        return response()->file($disk->path($path), [
            'Content-Type'  => $disk->mimeType($path),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}

