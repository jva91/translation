<?php

namespace Jva91\Translation;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
          __DIR__ . '/../config/translation.php',
          'translation'
        );
        
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
          __DIR__ . '/../config/translation.php' => config_path('translation.php'),
        ], 'config');
        
    
        if ($this->app->runningInConsole()) {
            $this->commands([
              Commands\RemoveUnusedTranslations::class,
            ]);
        }
    }
    
}
