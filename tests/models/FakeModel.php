<?php


namespace Jva91\Translation\Tests\Models;


use Illuminate\Database\Eloquent\Model;
use Jva91\Translation\Traits\TranslationsTrait;

class FakeModel extends Model
{
    use TranslationsTrait;
    
    public static $transFields = ['name'];
    protected $table = 'fake';
    protected $guarded = [];
    
    
    
}