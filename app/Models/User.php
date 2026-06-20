<?php

namespace App\Models;

use App\Observers\UserObserver;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'current_team_id', 'photo', 'phone', 'timezone', 'locale', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    public function allTeams()
    {
        return $this->ownedTeams->merge($this->teams)->unique('id');
    }

    public function ownedContacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'owner_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Uses the 'role' string column on users table (single role per user).
    public function hasRole(string ...$names): bool
    {
        return in_array($this->role, $names, true);
    }

    public function isSuperAdmin(): bool { return $this->role === \App\Support\Roles::SUPER_ADMIN; }
    public function isAdmin(): bool      { return $this->role === \App\Support\Roles::ADMIN; }
    public function isManager(): bool    { return $this->role === \App\Support\Roles::MANAGER; }
    public function isClerk(): bool      { return $this->role === \App\Support\Roles::CLERK; }

    public function switchTeam(Team $team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }
        $this->forceFill(['current_team_id' => $team->id])->save();
        $this->setRelation('currentTeam', $team);
        return true;
    }

    public function belongsToTeam(Team $team): bool
    {
        return $this->id === $team->owner_id || $this->teams->contains($team);
    }
}
