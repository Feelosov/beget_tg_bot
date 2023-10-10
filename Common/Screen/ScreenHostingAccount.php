<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

class ScreenHostingAccount extends Singleton
{
    use ScreenTrait;

    /**
     * Управление аккаунтом по API: выбор действия
     *
     * @param  string $account_name
     * @return array|string
     */
    public function manage_account(string $account_name, bool $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран управления аккаунтом.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";

        $buttons = Button::_hosting()->account()->case__account_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Информация о тарифном плане
     *
     * @param  string $account_name
     * @param  string $additional_text
     * @return array|string
     */
    public static function plan_info(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Информация о тарифе.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Если увидели значок ⬅️, значит в этом параметре аккаунт приблизился к пределу. ";
        $text .= "Обратите внимание, что показатели нагрузки обычно выражаются в средних значениях.";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->account()->case__account_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }


    /**
     * Информация о сервере
     *
     * @param  string $account_name
     * @param  string $additional_text
     * @return array|string
     */
    public static function server_info(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Информация о сервере.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Обратите внимание, что показатели нагрузки обычно выражаются в средних значениях.";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->account()->case__account_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран приглашения к переключению SSH
     *
     * @param  string $account_name
     * @param  string $additional_text
     * @return array|string
     */
    public static function toggle_ssh(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран переключения SSH.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Метод включает <code>1</code> или выключает <code>0</code> SSH для всего аккаунта - если передано значение без логина или для определенного FTP-аккаунта - если передан FTP-логин. ";
        $text .= "\n\n";
        $text .= "<u>Важно</u>: отключение для всего аккаунта не означает отключение для каждого FTP-аккаунта, и аналогично для включения. ";
        $text .= "Переключение может происходить с отложенным отображением в ПУ, но оно происходит - уточнение ТП.";
        $text .= "\n\n";
        $text .= "Пример: <code>1</code> - включит для всего аккаунта\n";
        $text .= "Пример: <code>0 ftp_login</code> - отключит для ftp_login";
        $text .= "\n\n";
        $text .= "<u>Впишите требуемое значение</u>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->account()->case__account_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }
}
