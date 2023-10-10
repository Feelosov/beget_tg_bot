<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

class ScreenHosting extends Singleton
{
    use ScreenTrait;

    /**
     * Экран со списком действий на хостинге
     *
     * @param  string       $account_name
     * @param  boolean      $return_name
     * @return array|string
     */
    public function categories(string $account_name, $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран выбора категории действий на хостинге.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Выберите категорию.";

        $buttons = Button::_hosting()->case__categories($account_name);

        return  ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Возвращает список экранов области действий Аккаунты
     *
     * @return ScreenHostingAccount
     */
    public function account(): ScreenHostingAccount
    {
        return ScreenHostingAccount::__get_instance();
    }

    /**
     * Возвращает список экранов области действий Бэкапы
     *
     * @return ScreenHostingBackups
     */
    public function backups(): ScreenHostingBackups
    {
        return ScreenHostingBackups::__get_instance();
    }

    /**
     * Возвращает список экранов области действия Cron
     *
     * @return ScreenHostingCron
     */
    public function cron(): ScreenHostingCron
    {
        return ScreenHostingCron::__get_instance();
    }

    /**
     * Возвращает список экранов области действий FTP
     *
     * @return ScreenHostingFTP
     */
    public function ftp(): ScreenHostingFTP
    {
        return ScreenHostingFTP::__get_instance();
    }
}
