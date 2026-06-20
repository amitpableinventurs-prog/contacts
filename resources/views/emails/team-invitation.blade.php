<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Team invitation</title></head>
<body style="font-family: -apple-system, system-ui, sans-serif; max-width: 560px; margin: 0 auto; padding: 24px; color: #111; line-height: 1.5;">
    <h2 style="margin: 0 0 8px;">You've been invited to <strong>{{ $invitation->team->name }}</strong></h2>
    <p>{{ $invitation->invitedBy->name }} ({{ $invitation->invitedBy->email }}) invited you to join their workspace as a <strong>{{ $invitation->role }}</strong>.</p>
    <p style="margin: 24px 0;">
        <a href="{{ $acceptUrl }}" style="background:#7c3aed;color:white;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;">Accept invitation</a>
    </p>
    <p style="font-size: 12px; color: #666;">Or paste this URL in your browser: <br><code>{{ $acceptUrl }}</code></p>
    @if ($invitation->expires_at)
        <p style="font-size: 12px; color: #666;">This invitation expires {{ $invitation->expires_at->diffForHumans() }}.</p>
    @endif
</body>
</html>
