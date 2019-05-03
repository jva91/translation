<?php


namespace Jva91\Translation\Models;


use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('translation.table_name'));
    }
    
    protected $guarded = [];
}