<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use romanzipp\PreviouslyDeleted\Tests\Support\HashedAttributesDeletingModel;
use romanzipp\PreviouslyDeleted\Tests\Support\HashedAttributesInvalidAlgorithmDeletingModel;

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

    public function testInvalidAlgoritm()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hashing algorithm "foobar" is not available');

        $model = HashedAttributesInvalidAlgorithmDeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->delete();
    }
}
