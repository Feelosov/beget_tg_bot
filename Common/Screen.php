<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\Common\Screen\ScreenBase;
use dev_bots_ru\Common\Screen\ScreenCloud;
use dev_bots_ru\Common\Screen\ScreenHosting;

// TODO: сделать обработку ответов от экранов

class Screen
{
    /**
     * Текст конечного экрана, чтобы можно было достать в любом месте
     *
     * @var string
     */
    public static $text = '';

    /**
     * Кнопки конечного экрана, чтобы можно было достать в любом месте
     *
     * @var array
     */
    public static $buttons = [];

    /**
     * Базовые экраны
     *
     * @return ScreenBase
     */
    public static function _base(): ScreenBase
    {
        return ScreenBase::__get_instance();
    }

    /**
     * Экраны хостингового аккаунта
     *
     * @return ScreenHosting
     */
    public static function _hosting(): ScreenHosting
    {
        return ScreenHosting::__get_instance();
    }

    /**
     * Экраны облачного аккаунта
     *
     * @return ScreenCloud
     */
    public static function _cloud(): ScreenCloud
    {
        return ScreenCloud::__get_instance();
    }
}
