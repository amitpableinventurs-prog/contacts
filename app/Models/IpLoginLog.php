<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpLoginLog extends Model
{
    protected $table = 'ip_login_logs';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'session_id',
        'browser',
        'device',
        'platform',
        'logout_at',
    ];

    protected $casts = [
        'logout_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Duration in human-readable form, or null if not logged out yet. */
    public function getDurationAttribute(): ?string
    {
        if (! $this->logout_at) return null;
        $seconds = $this->created_at->diffInSeconds($this->logout_at);
        if ($seconds < 60)   return "{$seconds}s";
        if ($seconds < 3600) return round($seconds / 60).'m';
        return round($seconds / 3600, 1).'h';
    }
}
