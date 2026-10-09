<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class SeederGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_seed_inserts_nothing()
    {
        $this->app['env'] = 'production';

        $this->artisan('db:seed', ['--force' => true])->assertSuccessful();

        $this->assertSame(0, DB::table('users')->count());
    }

    public function test_production_rollback_keeps_the_tables()
    {
        $this->app['env'] = 'production';

        try {
            $this->artisan('migrate:rollback', ['--force' => true])->run();
            $this->fail('rollback ran in production');
        } catch (RuntimeException $e) {
            $this->assertSame('refusing to drop production tables', $e->getMessage());
        }

        $this->assertTrue(Schema::hasColumn('users', 'roleID'));
    }

    public function test_testing_seed_creates_the_demo_admin()
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'demo-admin@example.test', 'roleID' => 1]);
    }
}
