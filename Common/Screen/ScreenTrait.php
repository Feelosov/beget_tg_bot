<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Screen;

/**
 * Вспомогательные методы для экрана
 */
trait ScreenTrait
{
    /**
     * Наполняет экран текстом и кнопками
     *
     * @param string $title
     * @param string $content
     * @param array $buttons
     * @return array
     */
    public static function __create_screen(string $text, array $buttons): array
    {
        $screen['text'] = $text;
        $screen['buttons'] = $buttons;
        Screen::$text = $screen['text'];
        Screen::$buttons = $screen['buttons'];

        return $screen;
    }
}
