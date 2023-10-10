<?php

namespace dev_bots_ru\Common\Patterns;

/**
 * Базовый класс для создания синглотонов php 8.2
 */
abstract class Singleton
{
    /**
     * @var [type]
     */
    private static $instance = null;

    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     *
     */
    final private function __clone()
    {
    }

    /**
     * 
     */
    final public function __wakeup()
    {
    }

    /**
     * Вернем экземпляр класса, из которого вызывается создание одиночки
     *
     * @return static
     */
    final public static function __get_instance(): static
    {
        $called_class_name = get_called_class();
        if (!isset(self::$instance[$called_class_name])) :
            self::$instance[$called_class_name] = new $called_class_name();
        endif;

        return self::$instance[$called_class_name];
    }
}
