<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Tests\Unit;

use DanielDeWit\LighthousePaperclip\Providers\LighthousePaperclipServiceProvider;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Nuwave\Lighthouse\LighthouseServiceProvider;
use Nuwave\Lighthouse\Testing\TestingServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class UnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function getPackageProviders($app): array
    {
        return [
            LighthouseServiceProvider::class,
            LighthousePaperclipServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $app['config'];

        $config->set('app.debug', true);
    }
}
