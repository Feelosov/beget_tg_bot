<?php

namespace dev_bots_ru\General;

class Config
{
    /**
     * Переключение режима разработчика. Большинству продвинутых пользователей именного этого бота подойдет TRUE
     *
     * @return boolean
     */
    public static function get__dev_mode_status(): bool
    {
        return true;
    }

    // --------------------------------
    // Директории бота общие
    // --------------------------------

    /**
     * Вернем корневую директорию бота
     * 
     * @return string
     */
    public static function get__bot_root_dir(): string
    {
        return realpath(__DIR__ . '/..');
    }

    /**
     * Папка с базой данных
     *
     * @return string
     */
    public static function get__bot_db_dir(): string
    {
        return self::get__bot_root_dir() . '/DB';
    }

    /**
     * Папка со вспомогательными классами
     *
     * @return string
     */
    public static function get__bot_classes_common_dir(): string
    {
        return self::get__bot_root_dir() . '/Common';
    }

    /**
     * Папка со всеми обработчиками ТГ-запросов
     *
     * @return string
     */
    public static function get__bot_handlers_dir(): string
    {
        return self::get__bot_root_dir() . '/Handlers';
    }

    // --------------------------------
    // Директории и файлы из БД бота
    // --------------------------------

    /**
     * Путь до папки с изображениями диаграмм
     *
     * @param  string $account_name
     * @return string
     */
    public static function get__db_dir__bot_charts_dir(string $account_name): string
    {
        $charts_dir_path = self::get__db_file__bot_accounts_dir() . '/' . $account_name . '/charts';
        if (!is_dir($charts_dir_path)) {
            mkdir($charts_dir_path, 0755, true);
        }
        return $charts_dir_path;
    }

    /**
     * Путь к папке со всеми аккаунтами
     *
     * @return string
     */
    public static function get__db_file__bot_accounts_dir(): string
    {
        if (!is_dir(self::get__bot_db_dir() . '/accounts')) :
            mkdir(self::get__bot_db_dir() . '/accounts', 0755, true);
        endif;

        return self::get__bot_db_dir() . '/accounts';
    }

    /**
     * Путь к файлу БД с урл до бота
     *
     * @return string
     */
    public static function get__db_file__bot_url(): string
    {
        return self::get__bot_db_dir() . '/tg_bot_url';
    }

    /**
     * Путь к файлу БД с данными владельца
     * 
     * @return string
     */
    public static function get__db_file__tg_bot_owner(): string
    {
        return self::get__bot_db_dir() . '/tg_bot_owner';
    }

    /**
     * Путь к файлу БД с данными токена бота
     * 
     * @return string
     */
    public static function get__db_file__tg_bot_token(): string
    {
        return self::get__bot_db_dir() . '/tg_bot_token';
    }

    /**
     * Путь к файлу БД с данными секретного ключа бота из урл веб-хука
     * 
     * @return string
     */
    public static function get__db_file__tg_bot_secret_key(): string
    {
        return self::get__bot_db_dir() . '/tg_bot_secret_key';
    }

    /**
     * Путь к файлу БД с данными о последних действиях пользователя
     * 
     * @return string
     */
    public static function get__db_file__user_actions(): string
    {
        return self::get__bot_db_dir() . '/user_actions';
    }

    /**
     * Путь к файлу БД с данными ключа шифрования бота
     * 
     * @return string
     */
    public static function get__db_file__encryption_key(): string
    {
        return self::get__bot_db_dir() . '/encryption_key';
    }

    // --------------------------------
    // Параметры бота
    // --------------------------------

    /**
     * Список доступных действий в боте
     *
     * @return array
     */
    public static function get__bot_allowed_actions(): array
    {
        return [
            'message',
            'callback_query',
        ];
    }

    /**
     * Ограничение, сколько последних действий пользователя хранить в БД
     *
     * @return integer
     */
    public static function get__user_limit_actions_to_save(): int
    {
        return 5;
    }
}
