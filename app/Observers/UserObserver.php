<?php

namespace App\Observers;

use App\Models\Team;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $team = Team::create([
            'owner_id' => $user->id,
            'name' => "{$user->name}'s Workspace",
            'personal' => true,
        ]);

        $user->teams()->attach($team, ['role' => 'owner']);
        $user->forceFill(['current_team_id' => $team->id])->save();
    }
}
