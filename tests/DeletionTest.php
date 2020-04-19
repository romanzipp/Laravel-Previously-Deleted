<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use romanzipp\PreviouslyDeleted\Tests\Support\DeletingModel;
use romanzipp\PreviouslyDeleted\Tests\Support\SoftDeletingModel;

class DeletionTest extends TestCase
{
    public function testAttributeStored()
    {
        $model = DeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
        ]);
    }

    public function testNullAttributeNotStored()
    {
        $model = DeletingModel::query()->create([
            'email' => null,
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([]);
    }

    public function testSoftIgnoredSoftDeletion()
    {
        $this->ignoreSoftDeleted(true);

        $model = SoftDeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([]);
    }

    public function testSoftSoftDeletion()
    {
        $this->ignoreSoftDeleted(false);

        $model = SoftDeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->delete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
        ]);
    }

    public function testSoftForceDeletion()
    {
        $this->ignoreSoftDeleted(false);

        $model = SoftDeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->forceDelete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
        ]);
    }

    public function testSoftIgnoredForceDeletion()
    {
        $this->ignoreSoftDeleted(true);

        $model = SoftDeletingModel::query()->create([
            'email' => 'john@doe.com',
        ]);

        $model->forceDelete();

        $this->assertPreviouslyDeleted([
            ['email', 'john@doe.com'],
        ]);
    }
}
