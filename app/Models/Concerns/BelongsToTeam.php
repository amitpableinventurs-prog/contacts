<?php

namespace App\Models\Concerns;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    protected static function bootBelongsToTeam(): void
    {
        static::creating(function ($model) {
            if (! $model->team_id && auth()->check() && auth()->user()->current_team_id) {
                $model->team_id = auth()->user()->current_team_id;
            }
        });

        static::addGlobalScope('currentTeam', function (Builder $query) {
            if (auth()->check() && auth()->user()->current_team_id) {
                $query->where($query->getModel()->getTable().'.team_id', auth()->user()->current_team_id);
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeWithoutTeamScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('currentTeam');
    }
}
