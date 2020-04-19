<?php

namespace romanzipp\PreviouslyDeleted\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use romanzipp\PreviouslyDeleted\Traits\SavePreviouslyDeleted;

class SoftDeletingModel extends Model
{
    use SavePreviouslyDeleted;
    use SoftDeletes;

    protected $table = 'tests__soft_deleting';

    protected $guarded = [];

    protected $storeDeleted = [
        'email',
    ];
}
