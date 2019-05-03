<?php


namespace Jva91\Translation\Commands;


use Illuminate\Console\Command;

class RemoveUnusedTranslations extends Command
{
    protected $signature = 'translation:remove-unused';
    
    protected $description = 'Remove unused translations from database';
    
    public function handle()
    {
        //@todo implement this feature
    }
}
