<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

final class ActivityLogger
{
    /**
     * Log any action.
     *
     * @param  string      $action   e.g. "contact.created", "user.updated"
     * @param  Model|null  $entity   The model being acted upon
     * @param  array       $meta     Extra context (old values, new values, etc.)
     */
    public static function log(string $action, ?Model $entity = null, array $meta = []): void
    {
        try {
            $user = Auth::user();

            ActivityLog::create([
                'team_id'     => $user?->current_team_id,
                'user_id'     => $user?->id,
                'action'      => $action,
                'entity_type' => $entity ? class_basename($entity) : null,
                'entity_id'   => $entity?->getKey(),
                'metadata'    => $meta ?: null,
            ]);
        } catch (\Throwable) {
            // Never crash the app because of a logging failure.
        }
    }
}
