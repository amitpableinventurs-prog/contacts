<?php

namespace App\Support;

use App\Models\User;

final class ApiUserPayload
{
    public static function for(User $user): array
    {
        $managerPlus = $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);
        $adminPlus = $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => (bool) $user->is_active,
            'current_team_id' => $user->current_team_id,
            'team_name' => $user->currentTeam?->name,
            'permissions' => [
                'is_super_admin' => $user->isSuperAdmin(),
                'is_admin' => $user->isAdmin(),
                'is_manager' => $user->isManager(),
                'is_clerk' => $user->isClerk(),
                'contacts_create' => $managerPlus,
                'contacts_update' => $managerPlus,
                'contacts_delete' => $adminPlus,
                'contacts_manage' => $managerPlus,
                'contacts_reactivate' => $adminPlus,
                'approve_contacts' => $adminPlus,
                'approve_edits' => $adminPlus,
                'manage_groups' => $managerPlus,
                'manage_tags' => $managerPlus,
                'view_tags' => $managerPlus,
                'manage_users' => $managerPlus,
                'advanced_search' => $adminPlus,
            ],
        ];
    }
}
