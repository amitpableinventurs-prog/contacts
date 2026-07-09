<?php

namespace App\Http\Controllers;

use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiTokensController extends Controller
{
    // API tokens would let restricted roles reach the API directly and
    // bypass their UI limits, so only Admin and above may manage them.
    protected function authorizeTokens(Request $request): void
    {
        abort_unless($request->user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN), 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeTokens($request);

        $tokens = $request->user()->tokens()->latest()->get();

        return view('profile.api-tokens', compact('tokens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeTokens($request);

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
        $this->authorizeTokens($request);

        $request->user()->tokens()->where('id', $tokenId)->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Token revoked.']);
    }
}
