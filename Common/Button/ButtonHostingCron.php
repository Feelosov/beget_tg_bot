<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Button\ButtonTrait;
use dev_bots_ru\Common\Screen;

class ButtonHostingCron extends Singleton
{
    use ButtonTrait;

    /**
     * Набор кнопок для раздела cron
     *
     * @return array
     */
    public function case__cron_actions(string $account_name): array
    {
        $buttons = [];

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Справка',
                Screen::_hosting()->cron()->get_help('', '', true)
            ),
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Список задач',
                Screen::_hosting()->cron()->get_list('', '', true)
            ),
            true
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Добавить задачу',
                Screen::_hosting()->cron()->add('', '', true)
            ),
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Изменить задачу',
                Screen::_hosting()->cron()->edit('', '', true)
            ),
            true
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                '⚠️ Удалить задачу',
                Screen::_hosting()->cron()->delete('', '', true)
            ),
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Изменить статус',
                Screen::_hosting()->cron()->change_status('', '', true)
            ),
            true
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Получить email',
                Screen::_hosting()->cron()->get_email('', '', true)
            ),
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Установить email',
                Screen::_hosting()->cron()->set_email('', '', true)
            ),
            true
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
