<?php

namespace romanzipp\PreviouslyDeleted\Traits;

use romanzipp\PreviouslyDeleted\Services\PreviouslyDeleted;

trait SavePreviouslyDeleted
{
    /**
     * Boot trait
     * @return void
     */
    protected static function bootSavePreviouslyDeleted(): void
    {
        static::deleting(function ($subject) {

            $ignoreSoftDeletes = config('previously-deleted.ignore_soft_deleted');

            if (!$subject->forceDeleting && $ignoreSoftDeletes) {
                return;
            }

            $service = new PreviouslyDeleted($subject);
            $service->setAttributes((array) $subject->storeDeleted);
            $service->save();
        });
    }
}
