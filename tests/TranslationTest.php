<?php

namespace Jva91\Translation\Tests;


use Jva91\Translation\Exceptions\ValueCanNotBeEmptyException;
use Jva91\Translation\Models\Translation;
use Jva91\Translation\Tests\Models\FakeModel;

class TranslationTest extends TranslationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
    
    /** @test */
    function can_get_translation_with_default_locale_in_config()
    {
        $this->app['config']->set('app.locale', 'nl_NL');
        $this->app['config']->set('translation.locales', ['en_GB', 'nl_NL']);
        $fakeModel = FakeModel::create(['name' => 'Voornaam']);
        $this->assertEquals('Voornaam', $fakeModel->name);
    }
    
    /** @test */
    function can_get_translation_with_default_locale_not_in_config()
    {
        $fakeModel = FakeModel::create(['name' => 'Voornaam']);
        $this->assertEquals('Voornaam', $fakeModel->name);
    }
    
    /** @test */
    function can_get_translation_with_locale_set()
    {
        $fakeModel = FakeModel::create(['name' => 'Voornaam']);
        $this->assertEquals('Voornaam', $fakeModel->name_nl_NL);
    }
    
    /** @test */
    function can_set_translation_with_locale_in_key()
    {
        $fakeModel = FakeModel::create(['name_nl_NL' => 'Voornaam']);
        $this->assertEquals('Voornaam', $fakeModel->name_nl_NL);
    }
    
    /** @test */
    function translation_is_empty_if_no_translation_for_locale()
    {
        $fakeModel = FakeModel::create(['name_nl_NL' => 'Voornaam']);
        $this->assertNull($fakeModel->name_en_GB);
    }
    
    /** @test */
    function translation_returns_translation_key_as_integer ()
    {
        $fakeModel = FakeModel::create(['name_nl_NL' => 'Voornaam']);
        $this->assertIsInt($fakeModel->getOriginalTranslationKey('name'));
    }
    
    /** @test */
    function translations_are_deleted_when_referencing_row_is_deleted()
    {
        $fakeModel = FakeModel::create(['name_nl_NL' => 'Voornaam', 'name_en_GB' => 'Firstname']);
        
        $translation_key = $fakeModel->getOriginalTranslationKey('name');
        $this->assertIsInt($translation_key);
        
        $fakeModel->delete();
        
        $translations = Translation::query()->where('key', '=', $translation_key)->get();
        $this->assertEmpty($translations);
    }
    
    /** @test */
    function translation_can_not_be_null()
    {
        try {
            FakeModel::create(['name' => null]);
        } catch (ValueCanNotBeEmptyException $e) {
            $this->assertEquals('Translation value can not be empty', $e->getMessage());
            return;
        }
    
        $this->fail();
    }
    
    /** @test */
    function translation_can_not_be_empty()
    {
        try {
            FakeModel::create(['name' => '']);
        } catch (ValueCanNotBeEmptyException $e) {
            $this->assertEquals('Translation value can not be empty', $e->getMessage());
            return;
        }
    
        $this->fail();
    }
    
    /** @test */
    function can_delete_translation_for_locale()
    {
        $fakeModel = FakeModel::create(['name_nl_NL' => 'Voornaam', 'name_en_GB' => 'Firstname']);
        $fakeModel->deleteForLocale('name', 'nl_NL');
        $this->assertNull($fakeModel->name_nl_NL);
        $this->assertEquals('Firstname', $fakeModel->name_en_GB);
    }
}
