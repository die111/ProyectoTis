<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Limpiar y migrar la base de datos de testing antes de cada suite de tests
        Artisan::call('migrate:fresh', ['--env' => 'testing', '--force' => true]);
    }
}
