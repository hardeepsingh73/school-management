<?php

namespace App\Policies;

use App\Helpers\Settings;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authenticated user can manage a specific target user.
     *
     * "Manage" can mean any administrative action such as:
     * - Editing their profile
     * - Changing their roles
     * - Deleting the account
     *
     * Logic:
     *  1. Users can always manage their own account.
     *  2. Super Admin can manage anyone.
     *  3. Admin can only manage themselves and regular users (not other admins or superadmins)
     *  4. Otherwise, deny.
     *
     * Role names are fetched from app settings for flexibility.
     *
     * @param  \App\Models\User  $authUser    The currently authenticated user.
     * @param  \App\Models\User  $targetUser  The user being managed.
     * @return bool
     */
    public function manage(User $authUser, User $targetUser): bool
    {
        //  Allow self-management
        if ($authUser->id === $targetUser->id) {
            return true;
        }

        // Retrieve role names from settings (with defaults)
        $roleSuperadmin = Settings::get('role_super_admin', 'superadmin');
        $roleAdmin = Settings::get('role_admin', 'admin');

        //  Superadmin can manage any user
        if ($authUser->hasRole($roleSuperadmin)) {
            return true;
        }

        //  Admin can only manage non-admin, non-superadmin users
        if ($authUser->hasRole($roleAdmin)) {
            return !$targetUser->hasRole($roleSuperadmin) && !$targetUser->hasRole($roleAdmin);
        }

        //  Default: deny management
        return false;
    }
}