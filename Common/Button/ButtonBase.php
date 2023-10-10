<?php

namespace dev_bots_ru\Common\Button;

use dev_bots_ru\Common\Button\ButtonTrait;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen;

class ButtonBase extends Singleton
{
    use ButtonTrait;

    /**
     * Вернем кнопку "В начало"
     *
     * @return array
     */
    public  function start(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            'В начало',
            'base_action?act=' . Screen::_base()->start(true)
        );
    }

    /**
     * Кнопка срочных функций
     *
     * @return array
     */
    public  function sos(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '🛟 SOS',
            'base_action?act=' . Screen::_base()->sos(true)
        );
    }

    /**
     * Установит ключ шифрования в БД бота
     *
     * @return array
     */
    public  function encryption_key_set(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '❇️ Установить ключ шифрования',
            'base_action?act=' . Screen::_base()->encryption_key_set(true)
        );
    }

    /**
     * Удалит ключ шифрования из БД бота
     *
     * @return array
     */
    public  function encryption_key_delete(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '🛑 Удалить ключ шифрования',
            'base_action?act=encryption_key_delete'
        );
    }

    /**
     * Кнопка списка аккаунтов
     *
     * @return array
     */
    public function accounts_list(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '🗂 Аккаунты',
            'base_action?act=' . Screen::_base()->accounts_list(true)
        );
    }

    /**
     * Кнопка добавления аккаунта
     *
     * @return array
     */
    public  function account_add(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '📗 Добавить данные аккаунта',
            'base_action?act=' . Screen::_base()->account_add(true)
        );
    }

    /**
     * Удалит все данные из БД бота
     *
     * @return array
     */
    public  function all_data_delete(): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '⛔️ Удалить все данные',
            'base_action?act=all_data_delete'
        );
    }

    /**
     * Кнопка удаления аккаунта
     *
     * @return array
     */
    public  function account_delete(string $account_name): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '📕 Удалить данные аккаунта',
            'base_action?login=' . $account_name . '&act=account_delete'
        );
    }

    /**
     * Кнопка определенного аккаунта
     *
     * @return array
     */
    public  function account_start(string $account_name): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            $account_name,
            'base_action?login=' . $account_name . '&act=account_start'
        );
    }

    /**
     * Кнопка аккаунта-облака
     *
     * @param  string $account_name
     * @return array
     */
    public function account_cloud(string $account_name): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '☁️ Облако',
            'cloud_action?login=' . $account_name . '&act=' . Screen::_cloud()->categories('', true)
        );
    }

    /**
     * Кнопка аккаунта-хостинга
     *
     * @param  string $account_name
     * @return array
     */
    public function account_hosting(string $account_name): array
    {
        return ButtonTrait::__create_button(
            'callback_data',
            '🌞 Хостинг',
            'hosting_action?login=' . $account_name . '&act=' . Screen::_hosting()->categories('', true)
        );
    }
}
