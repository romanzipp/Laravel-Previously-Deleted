<?php

namespace romanzipp\PreviouslyDeleted\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use romanzipp\PreviouslyDeleted\Traits\SavePreviouslyDeleted;

class HashedAttributesDeletingModel extends Model
{
    use SavePreviouslyDeleted;

    protected $table = 'tests__hashed_deleting';

    protected $guarded = [];

    protected $storeDeleted = [
        'email',
        'email_sha1' => 'sha1',
        'email_md5' => 'md5',
    ];
}
