<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 *
 * Represents a file uploaded and associated with a specific model in the system.
 * This model is polymorphic (`model()`), allowing files to be attached to any Eloquent model
 * along with metadata about file type, storage path, and uploader.
 */
class File extends Model
{
    use LogsActivity;

    // File type constants for consistent references in code
    public const TYPE_DOCUMENT = 1;
    public const TYPE_IMAGE    = 2;
    public const TYPE_AUDIO    = 3;
    public const TYPE_VIDEO    = 4;
    public const TYPE_ARCHIVE  = 5;

    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model_id',       // ID of the related model
        'model_type',     // Class name of the related model (polymorphic)
        'type_id',        // File type ID (see constants)
        'original_name',  // Original file name before upload
        'path',           // Storage path of the uploaded file
        'user_id',        // The user who uploaded the file
    ];

    /**
     * Get the list of allowed file types or return a type name by ID.
     *
     * @param  int|null  $type  The type ID (if null, returns the full list)
     * @return string|array<int, string>
     */
    public static function typeList(?int $type = null)
    {
        $list = [
            self::TYPE_DOCUMENT => 'Document',
            self::TYPE_IMAGE    => 'Image',
            self::TYPE_AUDIO    => 'Audio',
            self::TYPE_VIDEO    => 'Video',
            self::TYPE_ARCHIVE  => 'Archive',
        ];

        return $type !== null
            ? ($list[$type] ?? 'Unknown')
            : $list;
    }

    /**
     * Get the user who uploaded this file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The model that this file is attached to (polymorphic relation).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }
}
