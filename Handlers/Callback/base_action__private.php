<?php

use dev_bots_ru\Common\ResponseEnricher;
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

        case Screen::_base()->start(true):
            Screen::_base()->start();
            break;

        case Screen::_base()->sos(true):
            Screen::_base()->sos();
            break;

        case Screen::_base()->account_add(true):
            Screen::_base()->account_add();
            break;

        case Screen::_base()->accounts_list(true):
            Screen::_base()->accounts_list();
            break;

        case 'account_start':
            Screen::_base()->account_type_choose($account_name);
            break;

        case Screen::_base()->encryption_key_set(true):
            Screen::_base()->encryption_key_set();
            break;

        case 'encryption_key_delete':
            $text = ResponseEnricher::_base()->encryption_key_delete();
            Screen::_base()->sos();
            Screen::$text .= $text;
            break;

        case 'account_delete':
            $text = ResponseEnricher::_base()->account_delete($account_name);
            Screen::_base()->accounts_list();
            Screen::$text .= $text;
            break;

        case 'all_data_delete':
            ResponseEnricher::_base()->all_data_delete();
            exit; // (!) Удаляет все данные из бота, поэтому обработка заканчивается в ResponseEnricher

        default:
            Screen::_base()->start();
            Screen::$text .= "\n\n";
            Screen::$text .= "<u>Выбранное действие недоступно</u>";
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
