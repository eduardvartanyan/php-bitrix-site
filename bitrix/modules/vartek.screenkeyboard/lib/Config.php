<?php

namespace Vartek\ScreenKeyboard;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;

class Config
{
    const MODULE_ID = 'vartek.screenkeyboard';

    protected static array $defaults = [
        'auto_enable' => 'Y',
        'theme'       => 'light',
        'position'    => 'bottom',
        'animation'   => 'Y',
    ];

    public static function get(string $key, $default = null): string
    {
        if ($default === null && isset(static::$defaults[$key])) $default = static::$defaults[$key];

        return Option::get(static::MODULE_ID, $key, $default);
    }

    /**
     * @throws ArgumentOutOfRangeException
     */
    public static function set(string $key, $value): void
    {
        Option::set(static::MODULE_ID, $key, $value);
    }

    public static function all(): array
    {
        $result = [];
        foreach (static::$defaults as $key => $defaultValue) {
            $result[$key] = self::get($key, $defaultValue);
        }

        return $result;
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentException
     */
    public static function clear(): void
    {
        foreach (static::$defaults as $key => $defaultValue) {
            Option::delete(static::MODULE_ID, ['name' => $key]);
        }
    }
}