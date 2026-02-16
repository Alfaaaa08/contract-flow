<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase {
    protected function setUp(): void {
        $dbPath = __DIR__ . '/../database/testing.sqlite';

        putenv("DB_CONNECTION=sqlite");
        putenv("DB_DATABASE={$dbPath}");
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE']   = $dbPath;

        if (!file_exists($dbPath)) {
            touch($dbPath);
        }

        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver'                  => 'sqlite',
                'database'                => $dbPath,
                'prefix'                  => '',
                'foreign_key_constraints' => true,
            ],
            'database.connections.tenant' => [
                'driver'                  => 'sqlite',
                'database'                => $dbPath,
                'prefix'                  => '',
                'foreign_key_constraints' => true,
            ],
            'tenancy.database.auto_create_tenant_databases' => false,
            'tenancy.database.auto_delete_tenant_databases' => false,
            'tenancy.database.template_tenant_connection'   => null,
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path'     => 'database/migrations',
            '--force'    => true,
        ]);
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:rollback', [
            '--database' => 'sqlite',
            '--force'    => true,
        ]);

        parent::tearDown();

        $dbPath = __DIR__ . '/../database/testing.sqlite';
        if (file_exists($dbPath)) {
            unlink($dbPath);
        }
    }
}
