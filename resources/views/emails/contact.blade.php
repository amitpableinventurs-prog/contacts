<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $emailMessage->subject ?? 'Message' }}</title>
</head>
<body style="font-family: -apple-system, system-ui, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; color: #111;">
    <div style="white-space: pre-line; line-height: 1.6;">{!! $body !!}</div>

    @if ($trackingId)
        <img src="{{ url('/track/email/' . $trackingId . '.gif') }}" width="1" height="1" alt="" style="display:none" />
    @endif
</body>
</html>
