<?php

use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\Errors;
use dev_bots_ru\General\Parser;
use dev_bots_ru\General\Router;
use dev_bots_ru\Senders\TG;

/**
 * Чтоб этот камелКейз в аду горел или в песке растворился
 *
 * @return void
 */
function cloud_action__private()
{
    //
    $account_name = Router::$parameters['login'];
    $action = Router::$parameters['act'];

    //
    switch ($action):

            //
        default:
            Screen::_base()->account_type_choose($account_name);
            Screen::$text .= "\n\n";
            Screen::$text .= "<u>Бот пока что не имеет доступных действий с облаком</u>";
            break;

    endswitch;

    //
    if (Screen::$text) :
        TG::sendMessage(Parser::$tg_user_id, Screen::$text, Screen::$buttons);
    else :
        Errors::add__error_message(
            "Пустая текстовая часть при попытке отправить сообщение: " . $action,
            __FILE__,
            __LINE__
        );
        Errors::send__error_message();
        exit;
    endif;
}
