<?php

namespace romanzipp\PreviouslyDeleted\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PreviouslyDeletedProvider::class,
        ];
    }
}
