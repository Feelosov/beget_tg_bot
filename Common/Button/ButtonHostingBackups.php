<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Button\ButtonTrait;
use dev_bots_ru\Common\Screen;

class ButtonHostingBackups extends Singleton
{
    use ButtonTrait;

    /**
     * Набор кнопок для раздела бэкапов
     *
     * @return array
     */
    public function case__backup_actions(string $account_name): array
    {
        $buttons = [];

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список путей сайтов от корня',
                Screen::_hosting()->backups()->site_home_paths_list('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список рез. файл. копий',
                Screen::_hosting()->backups()->files_backup_list('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список рез. БД копий',
                Screen::_hosting()->backups()->mysqls_backup_list('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список файл., дир. из копии',
                Screen::_hosting()->backups()->files_list_by_path_and_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список БД из копии',
                Screen::_hosting()->backups()->mysqls_list_by_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                '⚠️ Восст. файл. по ID копии и пути',
                Screen::_hosting()->backups()->restore_files_by_path_and_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                '⚠️ Восст. БД по ID копии и имени',
                Screen::_hosting()->backups()->restore_mysql_by_db_and_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Выгруз. файл. по ID копии и пути',
                Screen::_hosting()->backups()->downld_files_by_path_and_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Выгруз. БД по ID копии имени',
                Screen::_hosting()->backups()->downld_mysql_by_db_and_id('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Статусы заданий восст. и выгруз.',
                Screen::_hosting()->backups()->backup_log('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_base()->account_hosting(
                $account_name
            )
        );

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
}
