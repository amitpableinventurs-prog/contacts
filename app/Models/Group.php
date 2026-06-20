<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['team_id', 'name', 'color'])]
class Group extends Model
{
    use BelongsToTeam;

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
