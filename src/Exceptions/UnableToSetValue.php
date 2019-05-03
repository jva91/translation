<?php


namespace Jva91\Translation\Exceptions;


use Exception;

class UnableToSetValue extends Exception
{
    public static function withValue(string $value)
    {
        return new static("Unable to save translation for value `{$value}`.");
    }
}