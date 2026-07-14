<?php

namespace App\Http\Controllers;

use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceController extends Controller
{
    /** True if the current user can manage workspace members (Admin or Super Admin). */
    private function canManage(): bool
    {
        return Auth::user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN);
    }

    public function members(): View
    {
        $team = Auth::user()->currentTeam;
        abort_unless($team, 404);

        $members     = $team->members()->get();
        $owner       = $team->owner;
        $invitations = TeamInvitation::where('team_id', $team->id)
            ->whereNull('accepted_at')
            ->latest()
            ->get();

        // Invite / revoke / remove is available to Admin and Super Admin.
        $canManage = $this->canManage();

        return view('workspace.members', compact('team', 'members', 'owner', 'invitations', 'canManage'));
    }

    public function invite(Request $request): RedirectResponse
    {
        abort_unless($this->canManage(), 403, 'Only Admins can invite workspace members.');

        $team = Auth::user()->currentTeam;
        abort_unless($team, 404);

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['required', 'in:admin,member'],
        ]);

        if (User::where('email', $data['email'])->whereHas('teams', fn ($q) => $q->where('teams.id', $team->id))->exists()) {
            return back()->withErrors(['email' => 'This user is already a member.']);
        }

        if (TeamInvitation::where('team_id', $team->id)->where('email', $data['email'])->whereNull('accepted_at')->exists()) {
            return back()->withErrors(['email' => 'An invitation is already pending for this email.']);
        }

        $invitation = TeamInvitation::create([
            'team_id'            => $team->id,
            'invited_by_user_id' => Auth::id(),
            'email'              => $data['email'],
            'role'               => $data['role'],
            'token'              => Str::random(48),
            'expires_at'         => now()->addDays(7),
        ]);

        Mail::to($data['email'])->queue(new TeamInvitationMail($invitation));

        ActivityLogger::log('member.invited', null, ['email' => $data['email'], 'role' => $data['role']]);

        return back()->with('toast', ['type' => 'success', 'message' => "Invitation sent to {$data['email']}."]);
    }

    public function revokeInvitation(TeamInvitation $invitation): RedirectResponse
    {
        abort_unless($this->canManage(), 403);

        $team = Auth::user()->currentTeam;
        abort_unless($team && $invitation->team_id === $team->id, 403);

        ActivityLogger::log('member.invitation_revoked', null, ['email' => $invitation->email]);
        $invitation->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Invitation revoked.']);
    }

    public function removeMember(User $member): RedirectResponse
    {
        abort_unless($this->canManage(), 403);

        $team = Auth::user()->currentTeam;
        abort_unless($team, 404);
        abort_if($member->id === $team->owner_id, 403, 'Cannot remove the workspace owner.');

        $team->members()->detach($member->id);

        if ($member->current_team_id === $team->id) {
            $personal = $member->ownedTeams()->where('personal', true)->first();
            $member->forceFill(['current_team_id' => $personal?->id])->save();
        }

        ActivityLogger::log('member.removed', $member, ['name' => $member->name]);

        return back()->with('toast', ['type' => 'success', 'message' => "{$member->name} removed."]);
    }

    public function showInvitation(string $token): View|Response
    {
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

        if (! $invitation->isPending()) {
            return view('workspace.invitation-expired');
        }

        return view('workspace.invitation', compact('invitation'));
    }

    public function acceptInvitation(string $token): RedirectResponse
    {
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();
        abort_unless($invitation->isPending(), 410, 'Invitation expired or already used.');

        $user = Auth::user();
        if (! $user || $user->email !== $invitation->email) {
            return redirect()->guest(route('login'))->with('toast', [
                'type'    => 'default',
                'message' => "Sign in as {$invitation->email} to accept the invitation.",
            ]);
        }

        DB::transaction(function () use ($invitation, $user) {
            $user->teams()->syncWithoutDetaching([
                $invitation->team_id => ['role' => $invitation->role],
            ]);
            $invitation->forceFill(['accepted_at' => now()])->save();
            $user->forceFill(['current_team_id' => $invitation->team_id])->save();
        });

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'message' => 'Welcome to '.$invitation->team->name.'.',
        ]);
    }

    public function switchTeam(Team $team): RedirectResponse
    {
        $user = Auth::user();
        abort_if($user->isClerk(), 403, 'Clerks cannot switch workspaces.');

        if ($user->switchTeam($team)) {
            return back()->with('toast', ['type' => 'success', 'message' => 'Switched to '.$team->name.'.']);
        }
        abort(403);
    }
}
