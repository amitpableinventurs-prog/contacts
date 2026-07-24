<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMessage;
use App\Support\ActivityLogger;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InboxController extends Controller
{
    /** Every role can message every other role, so the recipient list is everyone else. */
    private function recipientOptions(): \Illuminate\Support\Collection
    {
        return User::where('id', '!=', Auth::id())->orderBy('role')->orderBy('name')->get(['id', 'name', 'role']);
    }

    public function index(): View
    {
        $messages = UserMessage::where('recipient_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(25);

        return view('inbox.index', compact('messages'));
    }

    public function sent(): View
    {
        $messages = UserMessage::where('sender_id', Auth::id())
            ->with('recipient')
            ->latest()
            ->paginate(25);

        return view('inbox.sent', compact('messages'));
    }

    public function create(): View
    {
        return view('inbox.create', ['recipients' => $this->recipientOptions()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', 'different:'.Auth::id()],
            'body'         => ['required', 'string', 'max:5000'],
        ]);

        $message = UserMessage::create([
            'sender_id'    => Auth::id(),
            'recipient_id' => $data['recipient_id'],
            'body'         => $data['body'],
        ]);

        ActivityLogger::log('message.sent', null, ['to' => $message->recipient->name]);

        return redirect()->route('inbox.index')->with('toast', ['type' => 'success', 'message' => 'Message sent.']);
    }

    public function show(UserMessage $message): View
    {
        abort_unless($message->recipient_id === Auth::id() || $message->sender_id === Auth::id(), 403);

        if ($message->recipient_id === Auth::id() && ! $message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return view('inbox.show', compact('message'));
    }

    /**
     * A user can delete a message they sent or received. Admin can additionally
     * delete any message sitting in a Manager's or Clerk's inbox (moderation).
     */
    public function destroy(UserMessage $message): RedirectResponse
    {
        $actor = Auth::user();
        $isOwn = $message->recipient_id === $actor->id || $message->sender_id === $actor->id;
        $isAdminModeration = $actor->isAdmin() && $message->recipient->hasRole(Roles::MANAGER, Roles::CLERK);

        abort_unless($isOwn || $isAdminModeration, 403);

        $message->delete();

        return redirect()->route('inbox.index')->with('toast', ['type' => 'success', 'message' => 'Message deleted.']);
    }
}
