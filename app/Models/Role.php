<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * This model extends the Spatie Role model to include:
 * - Soft deletion functionality (keeps deleted records in the database with a deleted timestamp).
 * - Activity logging via the custom LogsActivity trait.
 *
 * @package App\Models
 *
 * @property int         $id          The unique identifier of the role.
 * @property string      $name        The name of the role.
 * @property string|null $guard_name  The guard name used by Laravel authentication.
 * @property \Carbon\Carbon|null $created_at  Timestamp when the role was created.
 * @property \Carbon\Carbon|null $updated_at  Timestamp when the role was last updated.
 * @property \Carbon\Carbon|null $deleted_at  Timestamp when the role was soft deleted.
 */
class Role extends SpatieRole
{
    // Apply soft deletes to store deleted roles without removing them from the database.
    use SoftDeletes;

    // Custom trait to log changes performed on the model (create, update, delete, etc.).
    use LogsActivity;
}
