<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\DB;

class ScreenBase extends Singleton
{
    use ScreenTrait;

    /**
     * Стартовый экран
     *
     * @return array|string
     */
    public function start(bool $return_name = false): array|string
    {
        if ($return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Стартовый экран.</b>";
        $text .= "\n\n";
        $text .= "Здесь вы можете выбрать аккаунт и срочное действие.";

        $buttons = [];
        if (!trim(DB::get__encryption_key())) :
            $buttons = Button::_base()->encryption_key_set();
        else :
            $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->accounts_list());
        endif;
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->sos());

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран срочных действий
     *
     * @return array|string
     */
    public function sos(bool $return_name = false): array|string
    {
        if ($return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран срочных действий.</b>";
        $text .= "\n\n";
        $text .= "Здесь вы можете удалить свои данные из БД бота (не с хостинга). ";
        $text .= "⚠️ Данные удаляются без дополнительного подтверждения.";

        $buttons = [];
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->encryption_key_delete());
        if (!trim(DB::get__encryption_key())) :
            $buttons[0][0]['text'] .= " (удалено)";
        endif;
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->all_data_delete());
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->start());

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Добавление ключа шифрования
     *
     * @return array|string
     */
    public function encryption_key_set(bool $return_name = false): array|string
    {
        if ($return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран установки ключа шифрования.</b>";
        $text .= "\n\n";
        $text .= "Здесь вы можете добавить в бота ключ шифрования. ";
        $text .= "Если были ранее зашифрованные данные, то ключ должен соответствовать параметрам предыдущей шифровки. ";
        $text .= "В ином случае доступ к ранее созданным аккаунтам будет невозможен.";
        $text .= "\n\n";
        $text .= "Если вы измените ключ шифрования по любой причине, то придется пересоздавать аккаунты с нуля несмотря на то, что они будут в списке.";
        $text .= "\n\n";
        $text .= "<u>Впишите ключ</u>";

        $buttons = [];
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->start());

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран выбора и добавления аккаунтов
     *
     * @param  boolean      $return_name
     * @return array|string
     */
    public function accounts_list(bool $return_name = false): array|string
    {
        if ($return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка аккаунтов.</b>";
        $text .= "\n\n";
        $text .= "Выберите аккаунт или создайте новый.";

        $accounts_list = scandir(Config::get__db_file__bot_accounts_dir());
        unset($accounts_list[array_search('.', $accounts_list)]);
        unset($accounts_list[array_search('..', $accounts_list)]);
        sort($accounts_list);

        $buttons = [];
        foreach ($accounts_list as $account) :
            $account_dir      = Config::get__db_file__bot_accounts_dir() . '/' . $account;
            $account_password = file_get_contents($account_dir . '/password');
            $account_iv       = file_get_contents($account_dir . '/iv');
            $account_tag      = file_get_contents($account_dir . '/tag');
            $account_ad       = file_get_contents($account_dir . '/additional_data');
            // Если хоть один файл пропал или нулевой, удаляем всю информацию об аккаунте
            if (!$account_password || !$account_iv || !$account_tag || !$account_ad) :
                DB::delete__account($account);
            else :
                $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->account_start($account), true);
            endif;
        endforeach;
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->account_add());
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->start());

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран добалвения данных аккаунта Бегет
     *
     * @return array|string
     */
    public function account_add(bool $return_name = false): array|string
    {
        if ($return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран добавления аккаунта в бота.</b>";
        $text .= "\n\n";
        $text .= "Напишите от аккаунта Beget логин потом пробел и потом пароль от API. ";
        $text .= "Если такой аккаунт уже существует, его данные будут перезаписаны. ";
        $text .= "Пароль должен быть не короче 6 знаков, а логин - не короче 5 и не длиннее 10 знаков.";
        $text .= "\n\n";
        $text .= "Пароль для API можно задать здесь: https://cp.beget.com/settings/access/api";
        $text .= "\n\n";
        $text .= "Пример:";
        $text .= "\n\n";
        $text .= "<code>myAccount my_pa\$\$w0rd</code>";
        $text .= "\n\n";
        $text .= "Требования:";
        $text .= "\n\n";
        $text .= "<code>";
        $text .= "- в логине разрешены только латинские буквы, цифры, подчеркивание.";
        $text .= "\n";
        $text .= "- в пароле недопустимы пробелы и имитация HMTL, PHP тегов.";
        $text .= "</code>";

        $buttons = [];
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->accounts_list());
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->start());

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран выбора типа аккаунта
     *
     * @param  string       $account_name
     * @param  boolean      $return_name
     * @return array|string
     */
    public function account_type_choose(string $account_name, bool $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран выбранного аккаунта.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "<u>Выберите тип аккаунта.</u>";
        $text .= "\n\n";
        $text .= "Удаление данных аккаунта происходит не с Бегета, а из бота, при этом подтверждение не запрашивается.";

        $buttons = [];
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->account_cloud($account_name, 'cloud'));
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->account_hosting($account_name), true);
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->account_delete($account_name));
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->accounts_list());
        $buttons = Button::_base()->__merge_buttons($buttons, Button::_base()->start());

        return ScreenTrait::__create_screen($text, $buttons);
    }
}
