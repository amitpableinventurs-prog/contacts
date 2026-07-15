<?php

namespace App\Policies;

use App\Models\ContactNote;
use App\Models\User;
use App\Support\Roles;

class ContactNotePolicy
{
    // Clerks can edit their own notes; Manager+ can edit any note.
    public function editNote(User $user, ContactNote $note): bool
    {
        return $note->contact->team_id === $user->current_team_id
            && ($note->user_id === $user->id || $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER));
    }
}
