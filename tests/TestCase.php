<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Temporary: enhanced DB query logging to storage/logs/query_debug.log to help
        // diagnose aborted transaction issues during tests. This logs the SQL, bindings,
        // connection name and a short stack trace so we can find where queries originate.
        DB::listen(function ($query) {
            try {
                $connection = $query->connectionName ?? (method_exists($query, 'connection') ? $query->connection->getName() : config('database.default'));
            } catch (\Throwable $e) {
                $connection = config('database.default');
            }

            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
            $frames = [];
            foreach ($backtrace as $i => $frame) {
                if (isset($frame['file']) && strpos($frame['file'], base_path()) === 0) {
                    $frames[] = ($frame['file'] ?? '') . ':' . ($frame['line'] ?? '');
                }
            }

            $line = '['.date('Y-m-d H:i:s')."] [conn:{$connection}] SQL: {$query->sql} | bindings: ".json_encode($query->bindings).
                " | trace: ".json_encode($frames).PHP_EOL;

            file_put_contents(app()->storagePath('logs'.DIRECTORY_SEPARATOR.'query_debug.log'), $line, FILE_APPEND);
        });

        // Also register a global throwable logger that will capture the very first
        // exception during tests (useful to catch the original error that causes
        // Postgres to mark the transaction as aborted). We write to a dedicated
        // file first_exception_debug.log and avoid overwriting it once written.
        static $firstExceptionLogged = false;
        $logFirstException = function ($e) use (&$firstExceptionLogged) {
            if ($firstExceptionLogged) {
                return;
            }
            $firstExceptionLogged = true;

            $payload = [
                'time' => date('Y-m-d H:i:s'),
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'stack' => $e->getTraceAsString(),
                'request_uri' => request()->fullUrlSafe ?? null,
                'server' => php_uname(),
            ];

            file_put_contents(app()->storagePath('logs'.DIRECTORY_SEPARATOR.'first_exception_debug.log'), json_encode($payload, JSON_PRETTY_PRINT), FILE_APPEND);
        };

        // Register both exception and shutdown handlers to increase the chance
        // of capturing the root cause even if it bubbles to PHP fatal level.
        set_exception_handler(function ($e) use ($logFirstException) {
            $logFirstException($e);
            // rethrow so PHPUnit still fails normally
            throw $e;
        });

        register_shutdown_function(function () use ($logFirstException) {
            $err = error_get_last();
            if ($err && isset($err['type'])) {
                $e = new \ErrorException($err['message'], 0, $err['type'], $err['file'], $err['line']);
                $logFirstException($e);
            }
        });
        // We rely on RefreshDatabase in tests to handle migrations. Any global
        // test env-level cache/session driver overrides are provided via
        // phpunit.xml (CACHE_DRIVER/SESSION_DRIVER) so the app boots without
        // hitting DB-backed cache/session stores.
    }
}
