<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactEditRequest extends Model
{
    protected $fillable = [
        'contact_id', 'team_id', 'requested_by', 'reviewed_by',
        'status', 'changes', 'original', 'tags', 'photo_path', 'reviewed_at',
    ];

    protected $casts = [
        'changes'     => 'array',
        'original'    => 'array',
        'tags'        => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool { return $this->status === 'pending'; }
}
