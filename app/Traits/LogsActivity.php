<?php

namespace App\Traits;

use App\Helpers\Settings;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function (Model $model) use ($event) {
                $model->logActivity($event);
            });
        }
    }

    protected static function getModelEvents()
    {
        return ['created', 'updated', 'deleted'];
    }

    public function logActivity($event)
    {
        if (!Settings::get('activity_logs', true)) {
            return;
        }
        $description = $this->getActivityDescription($event);

        ActivityLog::create([
            'event' => $event,
            'description' => $description,
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'causer_id' => auth()->id(),
            'causer_type' => auth()->user() ? get_class(auth()->user()) : null,
            'properties' => $this->getActivityProperties($event),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    protected function getActivityDescription($event)
    {
        $modelName = class_basename($this);

        return match ($event) {
            'created' => "Created {$modelName}",
            'updated' => "Updated {$modelName}",
            'deleted' => "Deleted {$modelName}",
            default => "Performed {$event} on {$modelName}",
        };
    }

    protected function getActivityProperties($event)
    {
        $properties = [];

        if ($event === 'updated') {
            $properties = [
                'old' => Arr::except($this->getOriginal(), $this->getHidden()),
                'attributes' => Arr::except($this->getChanges(), $this->getHidden()),
            ];
        } elseif ($event === 'created' || $event === 'deleted') {
            $properties = [
                'attributes' => Arr::except($this->getAttributes(), $this->getHidden()),
            ];
        }

        return $properties;
    }
}
