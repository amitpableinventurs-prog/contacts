<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['team_id', 'contact_id', 'user_id', 'title', 'description', 'remind_at', 'status', 'notify_email', 'notify_sms', 'notified_at', 'completed_at'])]
class Reminder extends Model
{
    use BelongsToTeam;

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'notified_at' => 'datetime',
            'completed_at' => 'datetime',
            'notify_email' => 'boolean',
            'notify_sms' => 'boolean',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->remind_at->isPast();
    }
}
