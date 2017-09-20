<?php
# класс для настройки и получения настроек приложения

class Config {

    protected static $settings = array();

    # из класса Config (глобальные настройки приложения)

    public static function get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    # начальная глобальная настройка

    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }

}