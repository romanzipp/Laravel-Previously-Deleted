<?php

namespace romanzipp\PreviouslyDeleted\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use romanzipp\PreviouslyDeleted\Traits\SavePreviouslyDeleted;

class DeletingModel extends Model
{
    use SavePreviouslyDeleted;

    protected $table = 'tests__deleting';

    protected $guarded = [];

    protected $storeDeleted = [
        'email',
    ];
}
