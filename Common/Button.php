<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\Common\Button\ButtonCloud;
use dev_bots_ru\Common\Button\ButtonBase;
use dev_bots_ru\Common\Button\ButtonHosting;

class Button
{

    /**
     * Кнопки общие
     *
     * @return ButtonBase
     */
    public static function _base(): ButtonBase
    {
        return ButtonBase::__get_instance();
    }

    /**
     * Кнопки хостинга
     *
     * @return ButtonHosting
     */
    public  static function _hosting(): ButtonHosting
    {
        return ButtonHosting::__get_instance();
    }

    /**
     * Кнопки облака
     *
     * @return ButtonCloud
     */
    public  static function _cloud(): ButtonCloud
    {
        return ButtonCloud::__get_instance();
    }
}
