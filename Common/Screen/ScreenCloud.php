<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

/**
 * // TODO: Пока не готово, смотрим на востребованность хостингового бота и самому пока не нужно
 */
class ScreenCloud extends Singleton
{
    use ScreenTrait;

    /**
     * Экран со списком действий на облаке
     *
     * @param  string       $account_name
     * @param  boolean      $return_name
     * @return array|string
     */
    public static function categories(string $account_name, $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран выбора категории действий.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "<u>Бот пока что не имеет доступных действий с облаком</u>";

        $buttons = Button::_cloud()->case__categories($account_name);

        return  ScreenTrait::__create_screen($text, $buttons);
    }
}
