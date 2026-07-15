<?php

namespace App\Policies;

use App\Models\ContactNote;
use App\Models\User;
use App\Support\Roles;

class ContactNotePolicy
{
    // A Clerk can edit their own note; Manager+ can edit any note on the team.
    public function editNote(User $user, ContactNote $note): bool
    {
        return $note->team_id === $user->current_team_id
            && ($note->user_id === $user->id || $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER));
    }
}
