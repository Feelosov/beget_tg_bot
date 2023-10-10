<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Button\ButtonTrait;
use dev_bots_ru\Common\Screen;

/**
 * Не путать с кнопками, где есть в названии account: 
 * - там - общие действия с аккаунтом от API в целом (добавить, удалить)
 * - здесь - внутри области действия "Аккаунт" в API.
 */
class ButtonHostingAccount extends Singleton
{
    use ButtonTrait;

    /**
     * Набор кнопок для раздела Аккаунт
     *
     * @param  string $account_name
     * @return array
     */
    public function case__account_actions(string $account_name): array
    {
        $buttons = [];

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Тарифный план',
                Screen::_hosting()->account()->plan_info('', '', true)
            )
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Сервер',
                Screen::_hosting()->account()->server_info('', '', true)
            ),
            true
        );

        $buttons = ButtonTrait::__merge_buttons(
            $buttons,
            Button::_hosting()->hosting_action(
                $account_name,
                'Переключить SSH',
                Screen::_hosting()->account()->toggle_ssh('', '', true)
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
