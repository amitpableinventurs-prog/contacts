<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['team_id', 'name', 'slug', 'color'])]
class Tag extends Model
{
    use BelongsToTeam;

    protected static function booted(): void
    {
        static::saving(function (Tag $tag) {
            if (! $tag->slug && $tag->name) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class);
    }
}
