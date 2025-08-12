<?php

namespace App\Helpers;

use App\Models\User;

class RoleDataHelper
{
    /**
     * Retrieve users visible to the currently authenticated user based on their role.
     *
     * This method supports two modes:
     *  - Returning an Eloquent query builder object (when $returnQuery = true)
     *  - Returning the final user collection after applying filters (default)
     *
     * Role-based logic:
     *  - Super Admin: Can see all users.
     *  - Admin: Can see all users except Super Admins and other Admins.
     *  - Regular User: Can only see themselves.
     *
     * @param  bool  $returnQuery  Whether to return the Eloquent query builder instead of the collection.
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\Collection
     */
    public static function users(bool $returnQuery = false)
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Start base query including 'roles' relationship
        $query = User::with('roles');

        // Fetch role names from settings (with defaults)
        $userRole        = Settings::get('role_user', 'user');
        $adminRole       = Settings::get('role_admin', 'admin');
        $superAdminRole  = Settings::get('role_super_admin', 'superadmin');

        /**
         * ---- Access Control Rules ----
         */

        //  Super Admin → Can see all users
        if ($user->hasRole($superAdminRole)) {
            return $returnQuery ? $query : $query->latest()->get();
        }

        //  Admin → Exclude Super Admins & other Admins
        if ($user->hasRole($adminRole)) {
            $query->whereDoesntHave('roles', function ($q) use ($superAdminRole, $adminRole) {
                $q->whereIn('name', [$superAdminRole, $adminRole]);
            });
            return $returnQuery ? $query : $query->latest()->get();
        }

        //  Regular User → Only themselves
        if ($user->hasRole($userRole)) {
            $query->where('id', $user->id);
        }

        // Return either query builder or final result collection
        return $returnQuery ? $query : $query->latest()->get();
    }
}
