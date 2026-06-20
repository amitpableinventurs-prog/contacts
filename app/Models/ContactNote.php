<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactNote extends Model
{
    protected $table = 'contact_notes';

    protected $fillable = [
        'team_id',
        'contact_id',
        'user_id',
        'note_html',
    ];

    protected $casts = [
        'note_html' => 'string',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
