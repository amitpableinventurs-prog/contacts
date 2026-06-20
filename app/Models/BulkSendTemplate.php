<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkSendTemplate extends Model
{
    use BelongsToTeam, HasFactory;

    protected $fillable = ['team_id', 'user_id', 'name', 'channel', 'subject', 'body'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
