<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'team_id', 'contact_id', 'user_id',
    'from_email', 'to_email', 'subject', 'body_html', 'body_text',
    'status', 'tracking_id',
    'opens_count', 'clicks_count',
    'first_opened_at', 'last_opened_at', 'sent_at',
])]
class EmailMessage extends Model
{
    use BelongsToTeam;

    protected function casts(): array
    {
        return [
            'first_opened_at' => 'datetime',
            'last_opened_at' => 'datetime',
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
