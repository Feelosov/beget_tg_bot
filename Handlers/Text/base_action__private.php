<?php

use dev_bots_ru\Common\ResponseEnricher;
use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\DB;
use dev_bots_ru\General\Errors;
use dev_bots_ru\General\Parser;
use dev_bots_ru\General\Router;
use dev_bots_ru\Senders\TG;

function base_action__private()
{
    //
    $user_text = Parser::$tg_data['message']['text'];
    $account_name = Router::$parameters['login'] ?? 'Аккаунт не определен';
    $action = Router::$parameters['act'];

    //
    switch ($action):

        case 'account_add':
            $text = ResponseEnricher::_base()->account_add($user_text);
            Screen::_base()->accounts_list();
            Screen::$text .= "\n\n" . $text;
            break;

        case 'encryption_key_set':
            $text = ResponseEnricher::_base()->encryption_key_set($user_text);
            Screen::_base()->start();
            break;

        default:
            $text = "Бот не понял, что от него требуется.";
            Screen::_base()->start();
            Screen::$text = $text;
            Screen::$buttons = [];

    endswitch;

    //
    DB::clear__current_user_action();

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
    endif;
}
