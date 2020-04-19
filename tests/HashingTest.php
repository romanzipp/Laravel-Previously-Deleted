<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use romanzipp\PreviouslyDeleted\Tests\Support\HashedAttributesDeletingModel;

class HashingTest extends TestCase
{
    public function testStoringHashedAttributes()
    {
        $model = HashedAttributesDeletingModel::query()->create([
            'email' => 'john@doe.com',
            'email_sha1' => 'john@doe.com',
            'email_md5' => 'john@doe.com',
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
            ['email_sha1', sha1('john@doe.com')],
            ['email_md5', md5('john@doe.com')],
        ]);
    }

    public function testStoringAttributesWithNull()
    {
        $model = HashedAttributesDeletingModel::query()->create([
            'email' => 'john@doe.com',
            'email_sha1' => 'john@doe.com',
            'email_md5' => null,
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
            ['email_sha1', sha1('john@doe.com')],
        ]);
    }
}
