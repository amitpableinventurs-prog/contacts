<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiTokensController extends Controller
{
    public function index(Request $request): View
    {
        $tokens = $request->user()->tokens()->latest()->get();

        return view('profile.api-tokens', compact('tokens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'abilities' => ['nullable', 'array'],
        ]);

        $token = $request->user()->createToken(
            $data['name'],
            $data['abilities'] ?? ['*'],
        );

        return back()
            ->with('toast', ['type' => 'success', 'message' => 'Token created — copy it now, it will not be shown again.'])
            ->with('plainTextToken', $token->plainTextToken);
    }

    public function destroy(Request $request, int $tokenId): RedirectResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Token revoked.']);
    }
}
