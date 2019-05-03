<?php


namespace Jva91\Translation\Traits;

use DB;
use Exception;
use Jva91\Translation\Exceptions\LocaleNotExists;
use Jva91\Translation\Exceptions\UnableToSetValue;
use Jva91\Translation\Exceptions\ValueCanNotBeEmptyException;
use Jva91\Translation\Models\Translation;

trait TranslationsTrait
{
    /**
     * getAttribute
     *
     * @param string $column
     *
     * @return null
     * @throws Exception
     */
    public function getAttribute($column)
    {
        if (isset(static::$transFields)) {
            if (in_array($column, static::$transFields)) {
                return $this->getTranslationValue($column, app()->getLocale());
            }
            foreach ($this->getLocales() as $locale) {
                if (strpos($column, $locale) !== false) {
                    $keyWithoutLocale = substr($column, 0, strlen($column) - (strlen($locale) + 1));
                    if (in_array($keyWithoutLocale, static::$transFields)) {
                        return $this->getTranslationValue($keyWithoutLocale, $locale);
                    }
                }
            }
        }
    
        return parent::getAttribute($column);
    }
    
    /**
     * getTranslationValue
     *
     * @param string $transKey
     * @param string $locale
     *
     * @return null
     */
    private function getTranslationValue(string $transKey, string $locale)
    {
        if (is_null($transKey)) {
            return null;
        }
        $locale = $this->detectLocale($locale);
    
        $translation = Translation::query()
          ->where('key', '=', parent::getAttribute($transKey))
          ->where('locale', '=', $locale)
          ->first();
    
        return $translation ? $translation->value : null;
    }
    
    /**
     * @return array
     */
    private function getLocales():array
    {
        return array_merge(array_values(config('translation.locales')), array_values([config('app.locale')]));
    }
    
    /**
     * detectLanguage
     *
     * @param string $locale
     *
     * @return string
     */
    private function detectLocale(string $locale): string
    {
        if (!in_array($locale, $this->getLocales())) {
            throw LocaleNotExists::withLocale($locale);
        }
    
        return $locale;
    }
    
    /**
     * setAttribute
     *
     * @param string $column
     * @param string $value
     *
     * @return $this
     * @throws Exception
     */
    public function setAttribute($column, $value)
    {
        if (isset(static::$transFields)) {
            if (empty($value)) {
                throw new ValueCanNotBeEmptyException('Translation value can not be empty');
            }
            if (in_array($column, static::$transFields)) {
                $value = $this->setTranslationValue($column, $value, app()->getLocale());
            }
            foreach ($this->getLocales() as $locale) {
                if (strpos($column, $locale) !== false) {
                    $keyWithoutLocale = substr($column, 0, strlen($column) - (strlen($locale) + 1));
                    if (in_array($keyWithoutLocale, static::$transFields)) {
                        $column = $keyWithoutLocale;
                        $value = $this->setTranslationValue($keyWithoutLocale, $value, $locale);
                    }
                }
            }
        }
    
        return parent::setAttribute($column, $value);
    }
    
    /**
     * setTranslationValue
     *
     * @param string $column
     * @param string $value
     * @param string $locale
     *
     * @return $this
     * @throws Exception
     */
    private function setTranslationValue(string $column, string $value, string $locale)
    {
        $locale = $this->detectLocale($locale);
        if (!in_array($column, static::$transFields)) {
            throw UnableToSetValue::withValue($value);
        }
    
        try {
            DB::beginTransaction();
        
            if (parent::getAttribute($column) === null) {
                $transObj = new Translation();
                $transObj->locale = $locale;
                $transObj->value = $value;
                $max = DB::table(config('translation.table_name'))->max('key');
                $transObj->key = (int)$max + 1;
                $transObj->save();
            } else {
                $transObj = Translation::query()
                  ->firstOrNew([
                    'key' => parent::getAttribute($column),
                    'locale' => $locale
                ]);
                
                $transObj->value = $value;
                $transObj->save();
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw UnableToSetValue::withValue($value);
        }
    
        return $transObj->key;
    }
    
    /**
     * delete
     * @throws Exception
     */
    public function delete()
    {
        $keysToDelete = [];
        foreach (static::$transFields as $column) {
            $keysToDelete[] = parent::getAttribute($column);
        }
        parent::delete();
        Translation::query()
          ->whereIn('key', $keysToDelete)
          ->delete();
    }
    
    /**
     * @param $column
     *
     * @return int
     */
    public function getOriginalTranslationKey(string $column): int
    {
        return parent::getAttribute($column);
    }
    
    /**
     * @param string $column
     * @param string $locale
     */
    public function deleteForLocale(string $column, string $locale)
    {
        Translation::query()
          ->where('key', '=', parent::getAttribute($column))
          ->where('locale', '=', $locale)
            ->delete();
    }
}