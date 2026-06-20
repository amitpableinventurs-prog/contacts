<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Reminder</title></head>
<body style="font-family: -apple-system, system-ui, sans-serif; max-width: 560px; margin: 0 auto; padding: 24px; color: #111; line-height: 1.5;">
    <h2 style="margin: 0 0 8px;">{{ $reminder->title }}</h2>
    <p style="color: #666; margin: 0 0 16px;">Due {{ $reminder->remind_at->format('M j, Y g:i a') }}</p>
    @if ($reminder->description)
        <p>{{ $reminder->description }}</p>
    @endif
    @if ($reminder->contact)
        <p>Related contact: <strong>{{ $reminder->contact->name }}</strong> &middot; {{ $reminder->contact->email ?? $reminder->contact->phone ?? '' }}</p>
    @endif
</body>
</html>
