<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Support\Roles;

class ContactPolicy
{
    // All authenticated users with a team can view contacts.
    public function viewAny(User $user): bool
    {
        return $user->current_team_id !== null;
    }

    public function view(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
    }

    public function create(User $user): bool
    {
        return $user->current_team_id !== null;
    }

    // Only manager and above can edit contacts.
    public function update(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    // Manager and above can delete/trash contacts. Clerks cannot.
    public function delete(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    public function restore(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    public function forceDelete(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN);
    }

    // All roles can add notes and rate contacts.
    public function addNote(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
    }

    public function rate(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
    }
}
