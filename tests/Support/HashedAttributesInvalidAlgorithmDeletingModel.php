<?php

namespace romanzipp\PreviouslyDeleted\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use romanzipp\PreviouslyDeleted\Traits\SavePreviouslyDeleted;

class HashedAttributesInvalidAlgorithmDeletingModel extends Model
{
    use SavePreviouslyDeleted;

    protected $table = 'tests__hashed_invalid_algorithm_deleting';

    protected $guarded = [];

    protected $storeDeleted = [
        'email' => 'foobar',
    ];
}
