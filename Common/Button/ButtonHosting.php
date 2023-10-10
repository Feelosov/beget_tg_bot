<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Button\ButtonTrait;
use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Errors;

class ButtonHosting extends Singleton
{
    use ButtonTrait;

    /**
     * Кнопка любого действия на хостинге
     *
     * @return array
     */
    public function hosting_action(string $account_name, string $button_text, string $action_name): array
    {
        $action_data = 'hosting_action?login=' . $account_name . '&act=' . $action_name;

        // В Телеграме нельзя команду передавать длиннее 64 байт, поэтому такую кнопку пропустим и покажем ошибку
        if (strlen($action_data) > 64) :
            // TODO: дописать проверку на режим разработчика
            Errors::add__error_message(
                "Превышена длина команды на кнопке: " . $action_data,
                __FILE__,
                __LINE__
            );
            Errors::send__error_message();
        else :
            $button = ButtonTrait::__create_button(
                'callback_data',
                $button_text,
                $action_data
            );
            return $button;
        endif;
    }

    /**
     * Набор кнопок для экрана Хостинг
     *
     * @param  string $account_name
     * @return array
     */
    public function case__categories(string $account_name): array
    {
        $buttons = [];

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            $this->hosting_action(
                $account_name,
                'Аккаунт',
                Screen::_hosting()->account()->manage_account('', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            $this->hosting_action(
                $account_name,
                'Бэкапы',
                Screen::_hosting()->backups()->manage_backups('', true)
            ),
            true
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            $this->hosting_action(
                $account_name,
                'Cron',
                Screen::_hosting()->cron()->manage_cron('', true)
            ),
            true
        );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'DNS',
        //         'manage_dns'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'FTP',
        //         'manage_ftp'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'MySQL',
        //         'manage_mysql'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'Сайты',
        //         'manage_sites'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'Домены',
        //         'manage_domains'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'Почта',
        //         'manage_mails'
        //     ),
        //     true
        // );

        // $buttons = ButtonTrait::__merge_buttons(
        //     $buttons,
        //     $this->hosting_action(
        //         $account_name,
        //         'Статистика',
        //         'manage_stats'
        //     ),
        //     true
        // );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_base()->accounts_list()
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_base()->start()
        );

        return $buttons;
    }

    /**
     * Возвращает список кнопок области действий Аккаунты
     *
     * @return ButtonHostingAccount
     */
    public function account(): ButtonHostingAccount
    {
        return ButtonHostingAccount::__get_instance();
    }

    /**
     * Возвращает список кнопок области действий Бэкапы
     *
     * @return ButtonHostingBackups
     */
    public function backups(): ButtonHostingBackups
    {
        return ButtonHostingBackups::__get_instance();
    }

    /**
     * Возвращает список кнопок области действия Cron
     *
     * @return ButtonHostingCron
     */
    public function cron(): ButtonHostingCron
    {
        return ButtonHostingCron::__get_instance();
    }
}
