<?php


namespace Jva91\Translation\Exceptions;


use InvalidArgumentException;

class LocaleNotExists extends InvalidArgumentException
{
    public static function withLocale(string $locale)
    {
        return new static("There is no locale `{$locale}` specified.");
    }
}