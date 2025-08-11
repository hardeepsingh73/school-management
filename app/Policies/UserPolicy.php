<?php

namespace App\Policies;

use App\Helpers\Settings;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function manage(User $authUser, User $targetUser): bool
    {
        if ($authUser->id === $targetUser->id) {
            return true; // Self-access
        }

        $roleSuperadmin = Settings::get('role_super_admin', 'superadmin');
        $roleadmin = Settings::get('role_admin', 'admin');
     
        // Superadmin can manage anyone
        if ($authUser->hasRole($roleSuperadmin)||$authUser->hasRole($roleadmin)) {
            return true;
        }

    
        // Default deny
        return false;
    }
}
