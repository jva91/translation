<?php


namespace Jva91\Translation\Tests;


abstract class TranslationTestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    
    }
    
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Jva91\Translation\TranslationServiceProvider'];
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
          'driver'   => 'sqlite',
          'database' => ':memory:',
          'prefix'   => '',
        ]);
        
        $app['config']->set('app.locale', 'nl_NL');
        $app['config']->set('translation.locales', ['en_GB']);
    }
}