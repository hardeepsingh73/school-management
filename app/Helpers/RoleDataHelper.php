<?php

namespace App\Helpers;

use App\Models\User;

class RoleDataHelper
{
    public static function users(bool $returnQuery = false)
    {
        $user = auth()->user();
        $query = User::with('roles');

        $userRole = Settings::get('role_user', 'user');
        $adminRole = Settings::get('role_admin', 'admin');
        $superAdminRole = Settings::get('role_super_admin', 'superadmin');

        // Super admin sees all users
        if ($user->hasRole($superAdminRole)) {
            return $returnQuery ? $query : $query->latest()->get();
        }

        // Admin sees all users except super admins and other admins
        if ($user->hasRole($adminRole)) {
            $query->whereDoesntHave('roles', function ($q) use ($superAdminRole, $adminRole) {
                $q->whereIn('name', [$superAdminRole, $adminRole]);
            });
            return $returnQuery ? $query : $query->latest()->get();
        }

        // Regular user sees only self
        if ($user->hasRole($userRole)) {
            $query->where('id', $user->id);
        }

        return $returnQuery ? $query : $query->latest()->get();
    }
}
