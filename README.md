## Jva91/Translation
A easy package to manage database translations for Laravel

### Installation

#### Using composer

    composer require jva91/translation

or manually add in `composer.json`

    "require": {
    
        "jva91/translation": "0.1.x-dev"
    }
    
After add this line run `composer install`

This package package contains auto discovery. When auto discovery is disabled register the service provider in `config/app.php`

     'providers' => [
        // ...
        Jva91\Translation\TranslationServiceProvider::class,
     ]


### Publishing
This package publishes a config file.

    php artisan vendor:publish --provider="Jva91\Translation\TranslationServiceProvider" --tag="config"
    
### Migration
This package contains a default migration this will be executed with

    php artisan migrate
    
### Model
This package contains a default model

    Jva91\Translation\Models\Translation
    
### Usage
The package can be used by using the delivered trait `Jva91\Translation\Traits\TranslationsTrait`

    class FakeModel extends Model
    {
        use TranslationsTrait;
        
        public static $transFields = ['name'];
        // ...
        
`$transFields` are the columns in the table. This column must be an integer so the trait can store his translation.key integer in this column. There can be multiple columns in this array.

The trait uses the `config('app.locale')` setting. The extra translatable locales can be set in the config file `translation.php`
    
    'locales' => [
        'en_GB'
      ],
      
To get translation with the default `app.locale`

    $fakeModel->name

To get translation with a default locale

    $fakeModel->name_en_GB
    
To get the translation key integer from the model

    $fakeModel->getOriginalTranslationKey('name')
    
To delete a translation for a specific locale

    $fakeModel->deleteForLocale('name', 'nl_NL');
    
### Testing
This package is covered by unit tests

### Feature functions

- Console command to delete unused translations from table
