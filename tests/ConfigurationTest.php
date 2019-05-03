<?php


namespace Jva91\Translation\Tests;


class ConfigurationTest extends TranslationTestCase
{
    /** @test */
    function configuration_file_has_locales()
    {
        $this->assertArrayHasKey('locales', $this->app['config']['translation']);
    }
    
    /** @test */
    function configuration_file_has_table_name()
    {
        $this->assertArrayHasKey('table_name', $this->app['config']['translation']);
    }
    
    /** @test */
    function configuration_file_can_have_empty_locales()
    {
        $this->app['config']->set('translation.locales', []);
        $this->assertIsArray($this->app['config']['translation']);
        $this->assertGreaterThanOrEqual(0, $this->app['config']['translation.locales']);
    }
}