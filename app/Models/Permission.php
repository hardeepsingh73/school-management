<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 *
 * Represents an application permission, extending the base Spatie Permission model
 * to include:
 * - Soft deletion functionality (retains deleted records with a deleted_at timestamp).
 * - Activity logging through a custom LogsActivity trait.
 *
 * This model is typically used alongside roles to enforce access control in
 * the application using Spatie's permission package.
 *
 * @package App\Models
 *
 * @property int                         $id           The primary key of the permission.
 * @property string                      $name         The name of the permission (e.g., "edit users").
 * @property string|null                 $guard_name   The guard to which the permission applies (e.g., "web", "api").
 * @property \Carbon\Carbon|null         $created_at   Timestamp when the permission was created.
 * @property \Carbon\Carbon|null         $updated_at   Timestamp when the permission was last updated.
 * @property \Carbon\Carbon|null         $deleted_at   Timestamp when the permission was soft deleted.
 */
class Permission extends SpatiePermission
{
    /**
     * Enables soft deletion so permissions are not permanently removed from the DB.
     */
    use SoftDeletes;

    /**
     * Logs model events (create, update, delete) for auditing purposes.
     */
    use LogsActivity;
}
