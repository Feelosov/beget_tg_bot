<?php

use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\Errors;
use dev_bots_ru\General\Parser;
use dev_bots_ru\General\Router;
use dev_bots_ru\Senders\TG;

function base_action__private()
{
    //
    $account_name = Router::$parameters['login'] ?? 'Аккаунт не определен';
    $action = Router::$parameters['act'];

    //
    switch ($action):
        case 'start':
            Screen::_base()->start();
            break;
        default:
            Screen::_base()->start();
            Screen::$text .= "\n\n";
            Screen::$text .= "<u>Указанная команда недоступна</u>";
            break;
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
