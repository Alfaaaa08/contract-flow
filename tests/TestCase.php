<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase {
    protected function setUp(): void {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        config(['tenancy.database.auto_create_tenant_databases' => false]);
        config(['tenancy.database.auto_delete_tenant_databases' => false]);

        config(['tenancy.database.template_tenant_connection' => null,]);
    }
}
