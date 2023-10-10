<?php

namespace dev_bots_ru\General;

class DB
{
    /**
     *  Запишем данные аккаунта
     *
     * @param  string  $account_name
     * @param  string  $encrypted_password
     * @param  string  $iv
     * @param  string  $tag
     * @param  string  $additional_data
     * @return boolean
     */
    public static function create__account(string $account_name, string $encrypted_password, string  $iv, string  $tag, string  $additional_data): bool
    {
        $account_dir = Config::get__db_file__bot_accounts_dir() . '/' . $account_name;
        if (!is_dir($account_dir)) :
            mkdir($account_dir, 0755, true);
        endif;
        file_put_contents($account_dir . '/password', $encrypted_password);
        file_put_contents($account_dir . '/iv', $iv);
        file_put_contents($account_dir . '/tag', $tag);
        file_put_contents($account_dir . '/additional_data', $additional_data);

        // TODO: дописать проверку успешности создания
        return true;
    }

    /**
     * Удаление данных об аккаунте из бота
     *
     * @param  string  $account_name
     * @return boolean
     */
    public static function delete__account(string $account_name): bool
    {
        self::delete__folder_tree(Config::get__db_file__bot_accounts_dir() . '/' . $account_name);

        // TODO: дописать проверку успешности удаления
        return true;
    }

    /**
     * Удаление данных об аккаунте из бота
     *
     * @param  string  $account_name
     * @return boolean
     */
    public static function delete__folder_tree($dir): bool
    {
        $files = scandir($dir);
        unset($files[array_search('.', $files)]);
        unset($files[array_search('..', $files)]);

        foreach ($files as $file) :
            is_dir("$dir/$file") ? self::delete__folder_tree("$dir/$file") : unlink("$dir/$file");
        endforeach;

        return rmdir($dir);
    }

    /**
     * Удалить данные бота из БД
     *
     * @return boolean
     */
    public static function delete__bot_data(): bool
    {
        self::set__encryption_key('');
        self::set__tg_bot_owner('');
        self::set__tg_bot_token('');
        self::set__tg_bot_url('');
        self::clear__all_user_actions();
        return true;
    }

    /**
     * Установить ключ шифрования
     *
     * @param  string  $key
     * @return boolean
     */
    public static function set__encryption_key(string $key): bool
    {
        file_put_contents(Config::get__db_file__encryption_key(), $key);
        // TODO: дописать проверку
        return true;
    }

    /**
     * Установить новый TG ID владельца бота
     *
     * @param  string  $tg_id
     * @return boolean
     */
    public static function set__tg_bot_owner(string $tg_id): bool
    {
        file_put_contents(Config::get__db_file__tg_bot_owner(), $tg_id);
        // TODO: дописать проверку
        return true;
    }

    /**
     * Установить новый TG токен бота
     *
     * @param  string  $token
     * @return boolean
     */
    public static function set__tg_bot_token(string $token): bool
    {
        file_put_contents(Config::get__db_file__tg_bot_token(), $token);
        // TODO: дописать проверку
        return true;
    }

    /**
     * Установить новый УРЛ бота
     *
     * @param  string  $token
     * @return boolean
     */
    public static function set__tg_bot_url(string $url): bool
    {
        file_put_contents(Config::get__db_file__bot_url(), $url);
        // TODO: дописать проверку
        return true;
    }

    /**
     * Ссылка на папку с диаграммами
     *
     * @param  string $account
     * @return string
     */
    public static function get__bot_charts_url(string $account): string
    {
        $charts_dir = realpath(Config::get__db_dir__bot_charts_dir($account));
        $url_path = str_replace(realpath(Config::get__bot_root_dir()), '', $charts_dir);
        $url = DB::get__bot_url() . '' . $url_path;
        return $url;
    }

    /**
     * Зашифрованный пароль
     *
     * @param  string $account_name
     * @return string
     */
    public static function get__encrypted_password(string $account_name): string
    {
        $account_dir = Config::get__db_file__bot_accounts_dir() . '/' . $account_name;
        $account_password = file_get_contents($account_dir . '/password');
        return $account_password;
    }

    /**
     * Зашифрованный вектор
     *
     * @param  string $account_name
     * @return string
     */
    public static function get__encrypted_iv(string $account_name): string
    {
        $account_dir = Config::get__db_file__bot_accounts_dir() . '/' . $account_name;
        $account_iv = file_get_contents($account_dir . '/iv');
        return $account_iv;
    }

    /**
     * Зашифрованный тег
     *
     * @param  string $account_name
     * @return string
     */
    public static function get__encrypted_tag(string $account_name): string
    {
        $account_dir = Config::get__db_file__bot_accounts_dir() . '/' . $account_name;
        $account_tag = file_get_contents($account_dir . '/tag');
        return $account_tag;
    }

    /**
     * Зашифрованные дополнительные данные
     *
     * @param  string $account_name
     * @return string
     */
    public static function get__encrypted_ad(string $account_name): string
    {
        $account_dir = Config::get__db_file__bot_accounts_dir() . '/' . $account_name;
        $account_ad = file_get_contents($account_dir . '/additional_data');
        return $account_ad;
    }

    /**
     * УРЛ бота
     *
     * @return string
     */
    public static function get__bot_url(): string
    {
        return file_get_contents(Config::get__db_file__bot_url());
    }

    /**
     * TG ID владельца
     *  
     * @return float
     */
    public static function get__bot_owner_tg_id(): float
    {
        return file_get_contents(Config::get__db_file__tg_bot_owner());
    }

    /**
     * Токен бота
     * 
     * @return string
     */
    public static function get__bot_token(): string
    {
        return file_get_contents(Config::get__db_file__tg_bot_token());
    }

    /**
     * TG ID бота
     * 
     * @return string
     */
    public static function get__bot_tg_id(): string
    {
        return explode(":", self::get__bot_token())[0];
    }

    /**
     * Название ключа в GET-параметре 
     *
     * @return string
     */
    public static function get__bot_tg_url__hook_secret_key_name(): string
    {
        return 'secret_key';
    }

    /**
     * Значение ключа в GET-параметре 
     * 
     * @return string
     */
    public static function get__bot_tg_url__hook_secret_key_value(): string
    {
        return file_get_contents(Config::get__db_file__tg_bot_secret_key());
    }

    /**
     * Ключ шифрования
     * 
     * @return string
     */
    public static function get__encryption_key(): string
    {
        return file_get_contents(Config::get__db_file__encryption_key());
    }

    // --------------------------------
    // Работа с данными пользователя
    // --------------------------------

    /**
     * Последние действия пользователя
     *
     * @return string
     */
    public static function get__user_actions(): string
    {
        return file_get_contents(Config::get__db_file__user_actions());
    }

    /**
     * Очистка текущего действия пользователя
     *
     * @return bool
     */
    public static function clear__current_user_action(): bool
    {
        self::add__user_action('');
        // TODO: дописать проверку
        return true;
    }

    /**
     * Очистка всех действий пользователя
     *
     * @return void
     */
    public static function clear__all_user_actions()
    {
        file_put_contents(Config::get__db_file__user_actions(), '');
        // TODO: дописать проверку
        return true;
    }

    /**
     * Добавить действие пользователя.
     *
     * @param  string $action
     * @return bool
     */
    public static function add__user_action(string $action): bool
    {
        $all_actions = explode("\n", file_get_contents(Config::get__db_file__user_actions()));
        array_unshift($all_actions, $action);
        $all_actions = array_slice($all_actions, 0, Config::get__user_limit_actions_to_save());
        file_put_contents(Config::get__db_file__user_actions(), implode("\n", $all_actions));
        // TODO: дописать проверку
        return true;
    }

    /**
     * Получим действие пользователя текущее (0) или предыдущие (1, 2, 3...).
     * По умолчанию возвращает текущее действие.
     *
     * @param  integer $action_number
     * @return string
     */
    public static function get__user_action(int $action_number = 0): string
    {
        $all_actions = explode("\n", file_get_contents(Config::get__db_file__user_actions()));
        return $all_actions[$action_number];
    }
}
