<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application for testing.
     */
    public function createApplication(): Application
    {
        $this->ensureTestEnvironmentFileExists();

        $app = require Application::inferBasePath().'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Ensure a minimal .env file exists so framework bootstrapping does not emit warnings.
     */
    protected function ensureTestEnvironmentFileExists(): void
    {
        $environmentFile = dirname(__DIR__).'/.env';

        if (is_file($environmentFile)) {
            return;
        }

        $contents = implode(PHP_EOL, [
            'APP_ENV=testing',
            'APP_DEBUG=true',
            'APP_KEY=base64:Y772ywamO7HME+GBpRMnBUIkmhpbx/NyTP/gzLXCIQQ=',
            'APP_URL=http://localhost',
        ]).PHP_EOL;

        if (file_put_contents($environmentFile, $contents) === false) {
            throw new \RuntimeException('Unable to create the temporary .env file for tests.');
        }
    }
}
