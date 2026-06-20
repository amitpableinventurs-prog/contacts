<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContactGroup extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'slug',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(
            Contact::class,
            'contact_contact_group',
            'contact_group_id',
            'contact_id'
        )->withTimestamps();
    }
}
