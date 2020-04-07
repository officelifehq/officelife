<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class AuditLogCollection
{
    /**
     * Prepare a collection of audit logs.
     *
     * @param mixed $logs
     *
     * @return Collection
     */
    public static function prepare($logs): Collection
    {
        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $logsCollection->push([
                'id' => $log->id,
                'action' => $log->action,
                'objects' => json_decode($log->objects),
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($log->author) ? null : $log->author->id,
                    'name' => is_null($log->author) ? $log->author_name : $log->author->name,
                ],
                'localized_audited_at' => $log->date,
                'audited_at' => $log->audited_at,
            ]);
        }

        return $logsCollection;
    }
}
