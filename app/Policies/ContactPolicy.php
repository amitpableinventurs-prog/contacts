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

    // Manager and above can add contacts. Clerks cannot.
    public function create(User $user): bool
    {
        return $user->current_team_id !== null
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    // Manager and above can edit contact details. Clerks can only add notes.
    public function update(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    // Manager and above can perform admin-level contact actions such as
    // status changes, merges, uploads/deletes, and deleting notes.
    public function manage(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id
            && $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
    }

    // All roles including Clerks can ban/reactivate contacts.
    public function banOrReactivate(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
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

    // Every role including Clerks can add notes; only Manager+ can edit/delete a contact
    // (which also gates deleting someone else's note — see destroyNote()).
    public function addNote(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
    }

    public function rate(User $user, Contact $contact): bool
    {
        return $contact->team_id === $user->current_team_id;
    }
}
