<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'team_id', 'contact_id', 'user_id',
    'channel', 'direction', 'status',
    'from_number', 'to_number', 'body',
    'twilio_sid', 'twilio_payload', 'sent_at',
])]
class Message extends Model
{
    use BelongsToTeam;

    protected function casts(): array
    {
        return [
            'twilio_payload' => 'array',
            'sent_at' => 'datetime',
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
}
