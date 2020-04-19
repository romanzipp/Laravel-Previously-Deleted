<?php

namespace romanzipp\PreviouslyDeleted\Traits;

use Illuminate\Database\Eloquent\Model;
use romanzipp\PreviouslyDeleted\Services\PreviouslyDeleted;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait SavePreviouslyDeleted
{
    /**
     * Boot trait.
     *
     * @return void
     */
    protected static function bootSavePreviouslyDeleted(): void
    {
        static::deleting(static function (Model $subject) {

            $service = new PreviouslyDeleted($subject);

            if ( ! $service->shouldStoreAttributes()) {
                return;
            }

            $service->setAttributes((array) $subject->storeDeleted);
            $service->save();
        });
    }
}
