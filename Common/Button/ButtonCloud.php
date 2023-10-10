<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Errors;

/**
 * // TODO: Пока не готово, смотрим на востребованность хостингового бота и самому пока не нужно
 */
class ButtonCloud extends Singleton
{
    /**
     * Кнопка любого действия на облаке
     *
     * @return array
     */
    public function cloud_action(string $account_name, string $button_text, string $action_name): array
    {
        $action_data = 'cloud_action?login=' . $account_name . '&act=' . $action_name;

        // В Телеграме нельзя команду передавать длиннее 64 байт
        if (strlen($action_data) > 64) :
            Errors::add__error_message(
                "Превышена длина команды на кнопке: " . $action_data,
                __FILE__,
                __LINE__
            );
            Errors::send__error_message();
            exit;
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
     * Набор кнопок для экрана Облако
     *
     * @param  string $account_name
     * @return array
     */
    public function case__categories(string $account_name): array
    {
        $buttons = [];

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
