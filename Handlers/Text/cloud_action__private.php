<?php

use dev_bots_ru\Common\Screen;
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
    $account_name = Router::$parameters['login'];
    $account_action = Router::$parameters['act'];

    Screen::_base()->account_type_choose($account_name);
    $text = "<u>Бот пока что не имеет доступных действий с облаком</u>";
    Screen::$text .= "\n\n";
    Screen::$text .= $text;
    TG::sendMessage(Parser::$tg_user_id, Screen::$text, Screen::$buttons);
}
