<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\PreviouslyDeleted\Models\DeletedAttribute;
use romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider;
use romanzipp\PreviouslyDeleted\Tests\Support\DeletingModel;
use romanzipp\PreviouslyDeleted\Tests\Support\HashedAttributesDeletingModel;
use romanzipp\PreviouslyDeleted\Tests\Support\SoftDeletingModel;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            PreviouslyDeletedProvider::class,
        ];
    }

    protected function assertPreviouslyDeleted(array $expect): void
    {
        $deleted = DeletedAttribute::query()->get();

        $this->assertCount(count($expect), $deleted);

        foreach ($deleted as $index => $item) {
            $this->assertEquals($expect[$index][0], $item->attribute);
            $this->assertEquals($expect[$index][1], $item->value);
        }
    }

    protected function ignoreSoftDeleted(bool $value): void
    {
        config(['previously-deleted.ignore_soft_deleted' => $value]);
    }

    protected function setUpDatabase(Application $app): void
    {
        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create((new DeletingModel)->getTable(), static function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->nullable();
                $table->timestamps();
            });

        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create((new SoftDeletingModel)->getTable(), static function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });

        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create((new HashedAttributesDeletingModel)->getTable(), static function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->nullable();
                $table->string('email_sha1')->nullable();
                $table->string('email_md5')->nullable();
                $table->timestamps();
            });
    }
}
