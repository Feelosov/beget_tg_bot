<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

class ScreenHostingSites extends Singleton
{
    use ScreenTrait;

    // /**
    //  * Экран со списком путей до сайта от корня
    //  *
    //  * @param  string       $account_name
    //  * @param  string       $additional_text
    //  * @param  boolean      $return_name
    //  * @return array|string
    //  */
    // public function site_home_paths_list(string $account_name, string $additional_text, bool $return_name = false): array|string
    // {
    //     if (!$account_name && !$additional_text && $return_name) :
    //         return __FUNCTION__;
    //     endif;

    //     $text = "<b>Экран списка путей сайтов от корня.</b>";
    //     $text .= "\n\n";
    //     $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
    //     $text .= "\n\n";
    //     $text .= "<u>Выберите действие.</u>";
    //     $text .= "\n\n";
    //     $text .= $additional_text;

    //     $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

    //     return ScreenTrait::__create_screen($text, $buttons);
    // }

}
