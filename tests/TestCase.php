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
        $app['config']->set('customer_payment.model', \TheLHC\CustomerPayment\Tests\User::class);
    }

    /**
     * Run migrations for tables only used for testing purposes.
     *
     * @return void
     */
    protected function runTestMigrations()
    {
        if (! $this->schema->hasTable('users')) {
            $this->schema->create('users', function ($table) {
                $table->increments('id');
                $table->string('email');
                $table->string('name');
                $table->string('stripe_id')->nullable();
            });
        }

        if (! $this->schema->hasTable('payment_profiles')) {
            $this->schema->create('payment_profiles', function ($table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('stripe_card_id')->nullable();
                $table->string('card_brand')->nullable();
                $table->string('card_last_four')->nullable();
                //$table->timestamps();
            });
        }

        if (! $this->schema->hasTable('custom_users')) {
            $this->schema->create('custom_users', function ($table) {
                $table->increments('id');
                $table->string('email');
                $table->string('fname');
                $table->string('lname');
                $table->string('company');
                $table->string('stripe_acct')->nullable();
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
        $this->schema->drop('payment_profiles');
    }

    protected function getPackageProviders($app)
    {
        return ['TheLHC\CustomerPayment\CustomerPaymentServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'PaymentProcessor' => 'TheLHC\CustomerPayment\Facades\PaymentProcessor'
        ];
    }


}
