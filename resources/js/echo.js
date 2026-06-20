import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Only initialize Echo when Reverb is actually configured. If we initialize
// with undefined values, Echo throws synchronously and the rest of app.js
// (including Alpine.start()) never runs — which silently breaks every dropdown
// and the command palette on the page.
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;
const reverbHost = import.meta.env.VITE_REVERB_HOST;

if (reverbKey && reverbHost) {
    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: reverbHost,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });
    } catch (e) {
        console.warn('[Echo] Realtime broadcasting disabled:', e?.message ?? e);
        window.Echo = null;
    }
} else {
    // Stub so callers can do `window.Echo?.channel(...)` without null guards.
    window.Echo = null;
}
