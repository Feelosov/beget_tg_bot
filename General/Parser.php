<?php

namespace dev_bots_ru\General;

use dev_bots_ru\General\Errors;

class Parser
{
    /**
     * Входящие данные из Телеграма
     * @var array
     */
    public static array $tg_data = [];

    /**
     * Результат обнаружения данных пользователя
     *
     * @var array default empty array
     */
    public static array $user_object = [];

    /**
     * Определенное скриптом действие бота = ''
     *
     * @var string default empty string
     */
    public static string $action_type = '';

    /**
     * Храним тип чата
     *
     * @var string  default empty string
     */
    public static string $chat_type = '';

    /**
     * Храним username пользователя
     *
     * @var string
     */
    public static string $tg_user_username;

    /**
     * Храним имя пользователя
     *
     * @var string
     */
    public static string $tg_user_first_name;

    /**
     * Храним фамилию пользователя
     *
     * @var string
     */
    public static string $tg_user_last_name;

    /**
     * Храним TG-ID пользователя
     *
     * @var float
     */
    public static float $tg_user_id;

    /**
     * Обращение к пользователю
     *
     * @var string
     */
    public static string $tg_user_appeal;

    /**
     * ID обновления
     *
     * @var string
     */
    public static string $update_id = '';

    /**
     * 
     */
    public static function run()
    {
        //
        self::$tg_data = json_decode(file_get_contents('php://input'), 1) ?: exit;

        //
        self::$update_id = self::$tg_data['update_id'];

        //
        self::is_user_object_exists();

        //
        if (self::$user_object != false) :
            self::is_our_bot();
            self::set_tg_user_data();
            if (self::$user_object['is_bot'] == false) :
                self::check_user_in_db();
            else :
                exit;
            endif;
        endif;

        //
        self::check_secret();

        //
        self::allowed_actions();

        //
        self::clear_message();

        //
        self::chat_type();

        //
        self::check_user_in_db();
    }

    /**
     * Проверим, есть ли в запросе данные о пользователе
     *
     * @return void
     */
    private static function is_user_object_exists()
    {
        foreach (self::$tg_data as $k => $v) :
            if (isset($v['from'])) :
                self::$user_object = $v['from'];
                break;
            endif;
        endforeach;
    }

    /**
     * Проверим, что это сообщение не от нашего бота и не от автоматизации чата
     *
     * @return void
     */
    private static function is_our_bot()
    {
        if (self::$user_object['is_bot'] == true) :
            if (self::$user_object['id'] == DB::get__bot_tg_id() || self::$user_object['username'] == 'GroupAnonymousBot') :
                exit();
            endif;
        endif;
    }

    /**
     * Определим данные пользователя
     *
     * @return void
     */
    private static function set_tg_user_data()
    {
        self::$tg_user_id = self::$user_object['id'];
        self::$tg_user_first_name = self::$user_object['first_name'];
        self::$tg_user_username = self::$user_object['username'] ?? self::$tg_user_first_name;
        self::$tg_user_last_name = self::$user_object['last_name'] ?? '';
        self::$tg_user_appeal = self::$tg_user_first_name;
    }

    /**
     * Проверка секретного слова из УРЛ
     *
     * @return void
     */
    private static function check_secret()
    {
        if ($_GET[DB::get__bot_tg_url__hook_secret_key_name()] != DB::get__bot_tg_url__hook_secret_key_value()) {
            Errors::add__error_message('Секретный ключ не подходит', 'ND', 'ND');
            Errors::send__error_message();
            exit('Secret key is wrong!');
        }
    }

    /**
     * Проверим, разрешено ли запрошенное действие и установим его
     *
     * @return void
     */
    private static function allowed_actions()
    {
        $is_action_allowed = false;
        foreach (Config::get__bot_allowed_actions() as $v) :
            if (isset(self::$tg_data[$v])) :
                $is_action_allowed = true;
                self::$action_type = $v;
            endif;
        endforeach;

        if ($is_action_allowed == false) :
            // TODO: тут можно дописать, чтобы какое-то сообщение пользователю отправлял
            exit();
        endif;
    }

    /**
     * Если текстовое, то очистим от вероятных тегов
     *
     * @return void
     */
    private static function clear_message()
    {
        if (isset(self::$tg_data['message']['text'])) :
            self::$tg_data['message']['text'] = trim(strip_tags(self::$tg_data['message']['text']));
        endif;

        if (isset(self::$tg_data['message']['caption'])) :
            self::$tg_data['message']['caption'] = trim(strip_tags(self::$tg_data['message']['caption']));
        endif;
    }

    /**
     * Определим данные чата
     *
     * @return void
     */
    private static function chat_type()
    {
        self::$chat_type = self::$tg_data['message']['chat']['type'] ?? self::$tg_data['callback_query']['message']['chat']['type'];
        if (self::$chat_type != 'private') :
            self::$tg_user_id = self::$tg_data['message']['chat']['id'];
            Errors::add__error_message("Бот только для приватного использования", 'ND', 'ND');
            Errors::send__error_message();
            exit;
        endif;
    }

    /**
     * Проверим, что пользователь есть в DB
     *
     * @return void
     */
    public static function check_user_in_db()
    {
        // TODO : переписать на DB
        if (self::$tg_user_id != DB::get__bot_owner_tg_id()) :
            Errors::add__error_message("Вы не зарегистрированы в боте", 'ND', 'ND');
            Errors::send__error_message();
            exit;
        endif;
    }
}
