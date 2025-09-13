<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Create the application instance for testing.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
            include __DIR__.'/../bootstrap/app.php';
            /**
             * The Laravel application instance.
             *
             * @var \Illuminate\Foundation\Application $app
             */
            $app = app();

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
