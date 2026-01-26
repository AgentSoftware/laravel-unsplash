<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Tests;

use AgentSoftware\Unsplash\UnsplashServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            UnsplashServiceProvider::class,
        ];
    }
}
