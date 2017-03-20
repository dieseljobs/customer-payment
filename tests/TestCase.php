<?php

namespace TheLHC\CustomerPayment\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TestCase extends BaseTestCase
{

    /**
     * Schema Helpers.
     */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }

    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    public static function setUpBeforeClass()
    {
        if (file_exists(__DIR__.'/../.env')) {
            $dotenv = new Dotenv(__DIR__.'/../');
            $dotenv->load();
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->schema = $this->app['db']->connection()->getSchemaBuilder();
        $this->runTestMigrations();
        $this->beforeApplicationDestroyed(function () {
            $this->rollbackTestMigrations();
        });
        Eloquent::unguard();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        //$dotenv = new Dotenv(__DIR__);
        //$dotenv->load();
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }

    /**
     * Run migrations for tables only used for testing purposes.
     *
     * @return void
     */
    protected function runTestMigrations()
    {
        if (! $this->schema->hasTable('slack_log')) {
            $this->schema->create('users', function ($table) {
                $table->increments('id');
                $table->string('email');
                $table->string('name');
                $table->string('stripe_id')->nullable();
                //$table->string('card_brand')->nullable();
                //$table->string('card_last_four')->nullable();
                //$table->timestamps();
            });
        }
    }

    /**
     * Rollback migrations for tables only used for testing purposes.
     *
     * @return void
     */
    protected function rollbackTestMigrations()
    {
        $this->schema->drop('users');
    }

    protected function getPackageProviders($app)
    {
        return ['TheLHC\CustomerPayment\CustomerPaymentServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'PaymentProcessor' => 'TheLHC\CustomerPayment\CustomerPaymentFacade'
        ];
    }


}
