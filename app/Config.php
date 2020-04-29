<?php

namespace App;

/**
 * Class Config
 * @package App
 */
class Config
{
    protected static array $attributes;

    /**
     * Config constructor.
     *
     * @return void
     */
    protected function __construct()
    {
        //
    }

    /**
     * @return void
     */
    protected function __clone()
    {
        //
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function __wakeup(): void
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    public static function init(array $attributes): void
    {
        static::$attributes = $attributes;
        static::prepareAttributes();
    }

    /**
     * @param string|null $key
     * @return array|string|null
     */
    public static function get(string $key = null)
    {
        if (is_null($key)) {
            return static::$attributes;
        }

        return static::$attributes[$key] ?? null;
    }

    protected static function prepareAttributes(): void
    {
        static::$attributes['MAIL_BLACK_DOMAIN_LIST'] = isset(static::$attributes['MAIL_BLACK_DOMAIN_LIST'])
            ? explode(',', static::$attributes['MAIL_BLACK_DOMAIN_LIST'])
            : [];
    }
}
