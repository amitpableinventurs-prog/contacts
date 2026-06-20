<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkSend extends Model
{
    use BelongsToTeam, HasFactory;

    protected $fillable = [
        'team_id', 'user_id', 'channel', 'subject', 'body',
        'contact_ids', 'total_count', 'sent_count', 'failed_count',
        'started_at', 'finished_at',
    ];

    protected $casts = [
        'contact_ids' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): string
    {
        if ($this->finished_at) return 'completed';
        if ($this->started_at) return 'running';
        return 'queued';
    }

    public function progress(): int
    {
        if ($this->total_count === 0) return 0;
        return (int) round((($this->sent_count + $this->failed_count) / $this->total_count) * 100);
    }
}
