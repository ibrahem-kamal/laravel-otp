<?php

namespace Ibrahemkamal\Otp\Tests;

use Ibrahemkamal\Otp\OtpServiceProvider;
use Ibrahemkamal\Otp\Tests\TestModels\TestUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public TestUser $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Ibrahemkamal\\Otp\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        $this->testUser = TestUser::create([
            'name' => 'test user',
            'phone' => '1111111111',
        ]);

    }

    protected function getPackageProviders($app)
    {
        return [
            OtpServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
        });

        $migration = include __DIR__.'/../database/migrations/create_laravel-otp_table.php.stub';
        $migration->up();
    }
}
