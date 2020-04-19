<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider;
use romanzipp\PreviouslyDeleted\Tests\Support\DeletingModel;
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

    public function setUpDatabase(Application $app): void
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
    }
}
