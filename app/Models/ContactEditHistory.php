<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactEditHistory extends Model
{
    protected $fillable = ['contact_id', 'user_id', 'changed_fields'];

    protected $casts = ['changed_fields' => 'array'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
