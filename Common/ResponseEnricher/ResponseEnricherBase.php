<?php

namespace dev_bots_ru\Common\ResponseEnricher;

use dev_bots_ru\Common\Encryption;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\DB;
use dev_bots_ru\General\Parser;
use dev_bots_ru\Senders\Beget_hosting;
use dev_bots_ru\Senders\TG;

class ResponseEnricherBase extends Singleton
{
    /**
     * Пытается вписать данные аккаунта в бот
     *
     * @param  string $login_password
     * @return string
     */
    public function account_add(string $login_password): string
    {
        TG::deleteMessage(
            Parser::$tg_data['message']['chat']['id'],
            Parser::$tg_data['message']['message_id']
        );

        $login_password = Parser::$tg_data['message']['text'];
        $login_password_array = explode(" ", $login_password);
        $pass = true;
        $text = '';

        // Проверим на лишние пробелы
        if (count($login_password_array) > 2) :
            $pass = false;
            $text .= "\n";
            $text .= "<b>- Введены лишние данные</b>";
        endif;

        // Проверим допустимость логина
        $login = trim($login_password_array[0]);
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $login) || !$login || strlen($login) < 5 || strlen($login) > 10) :
            $pass = false;
            $text .= "\n";
            $text .= "<b>- Проблемы в логине</b>";
        endif;

        // Проверим допустимость пароля
        $password = trim($login_password_array[1]);
        if (preg_match('/\s/', $password) || !$password || strlen($password) < 6) :
            $pass = false;
            $text .= "\n";
            $text .= "<b>- Проблемы в пароле</b>";
        endif;

        // Зашифруем данные и создадим файлы
        if ($pass) :

            // Зашифруем пароль и прочие данные
            $encrypted_data = Encryption::encrypt_data($password);

            // Вытянем из зашифрованных данных ключи
            $encrypted_password = $encrypted_data['encrypted_data'];
            $iv = $encrypted_data['iv'];
            $tag = $encrypted_data['tag'];
            $additional_data = $encrypted_data['additional_data'];

            // Расшифруем для сравнения
            $decrypted_password = Encryption::decrypt_data($encrypted_password, $iv, $tag, $additional_data);

            // Проверим успешность декодирования
            if ($decrypted_password !== $password) :
                $pass = false;
                $text .= "\n";
                $text .= "<b>❌ Шифрование не пройдено</b>";
            else :
                DB::create__account($login, $encrypted_password, $iv, $tag, $additional_data);
                $text .= "\n";
                $text .= "<b>✅ Шифрование пройдено</b>";
            endif;

            // TODO: дать описание
            $test_connection = Beget_hosting::userGetAccountInfo($login);
            if ($test_connection['status'] == false || $test_connection['status'] == 'error') :
                $pass = false;
                $text .= "\n";
                $text .= "<b>❌ Подключение к АПИ не произошло</b>";
            else :
                $text .= "\n";
                $text .= "<b>✅ Подключение к АПИ успешно</b>";
            endif;

        endif;

        // TODO: дать описание
        if ($pass) :

            $text .= "\n";
            $text .= "<b>✅ Данные аккаунта записаны</b>";
            $text .= "\n";
            $text .= "<b>✅ Создан аккаунт:</b> <code>" . $login . "</code>";

            if (
                $test_connection['answer']['status'] == 'success'
                && isset($test_connection['answer']['result']['plan_name'])
                && $test_connection['answer']['result']['plan_name']
            ) :
                $text .= "\n\n";
                $text .= "Тарифный план хостинга: <b>" . $test_connection['answer']['result']['plan_name'] . "</b>";
            endif;

        else :

            $text .= "\n";
            $text .= "<b>❌ Аккаунт не удалось создать</b>";
            DB::delete__account($login);
            $text .= "\n";
            $text .= "<b>✅ Данные аккаунта удалены</b>";

        endif;

        return $text;
    }

    /**
     * Удаление аккаунта
     *
     * @param  string $account_name
     * @return string
     */
    public function account_delete(string $account_name): string
    {
        $delete_result = DB::delete__account($account_name);
        $text = "\n\n";
        if ($delete_result) :
            $text .= "<u>Аккаунт успешно удален</u>";
        else :
            $text .= "<u>Аккаунт не удалось удалить</u>";
        endif;

        return $text;
    }

    /**
     * Удаление данных
     *
     * @return void
     */
    public function all_data_delete()
    {
        // TODO: сделать через DB
        $bot_token = DB::get__bot_token();
        $owner_tg_id = DB::get__bot_owner_tg_id();
        $secret_key_name = DB::get__bot_tg_url__hook_secret_key_name();
        $secret_key = DB::get__bot_tg_url__hook_secret_key_value();
        $base_url = DB::get__bot_url();

        DB::delete__bot_data();
        DB::delete__folder_tree(Config::get__db_file__bot_accounts_dir());

        // Сразу работаем с экраном, чтобы не захламлять switch/case в обработчике
        Screen::_base()->sos();
        Screen::$text = "<b>Удалены следующие данные:</b>\n";
        Screen::$text .= "- Ключ шифрования\n";
        Screen::$text .= "- Токен бота\n";
        Screen::$text .= "- TG ID владельца\n";
        Screen::$text .= "- Последние действия\n";
        Screen::$text .= "- УРЛ бота\n";
        Screen::$text .= "- Все данные об аккаунтах\n";
        Screen::$text .= "\n";
        Screen::$text .= "<b>Сохранены следующие данные:</b>\n";
        Screen::$text .= "- Секретный ключ из УРЛ вебхука\n";
        Screen::$text .= "\n";
        Screen::$text .= "<u>Сейчас этот бот стал бесполезен. Укажите вручную данные в файлах, чтобы бот снова заработал.</u>";

        // Последнее сообщение
        file_get_contents('https://api.telegram.org/bot' . $bot_token . '/sendMessage?chat_id=' . $owner_tg_id . '&parse_mode=html&text=' . urlencode(Screen::$text));

        // Снимем зависшие запросы 
        $url = $base_url . '/index.php?' . $secret_key_name . "=" . $secret_key;
        $allowed_updates = json_encode([
            'message',
            'edited_message',
            'channel_post',
            'edited_channel_post',
            'inline_query',
            'chosen_inline_result',
            'callback_query',
            'shipping_query',
            'pre_checkout_query',
            'poll',
            'poll_answer',
            'my_chat_member',
            'chat_member',
            'chat_join_request',
        ]);
        $max_connections = 100;
        $drop_pending_updates = true;

        file_get_contents('https://api.telegram.org/bot' . $bot_token . '/setwebhook?url=' . $url . '&allowed_updates=' . urlencode($allowed_updates) . '&max_connections=' . $max_connections . '&drop_pending_updates=' . $drop_pending_updates);
    }

    /**
     * Установим ключ шифрования
     *
     * @return string
     */
    public function encryption_key_set(string $user_crypto_key): string
    {
        // TODO : переписать в DB
        file_put_contents(Config::get__db_file__encryption_key(), $user_crypto_key);

        // TODO: дописать логику ошибки

        $text = "\n\n";
        $text .= "<u>Ключ установлен!</u>";

        return $text;
    }

    /**
     * Удаляет ключ шифрования
     *
     * @return string
     */
    public function encryption_key_delete(): string
    {
        // TODO : переписать в DB
        file_put_contents(Config::get__db_file__encryption_key(), '');

        $text = "\n\n";
        $text .= "<u>Ключ шифрования удален!</u>";
        $text .= "\n\n";
        $text .= " Дальнейшие манипуляции с аккаунтами невозможны.";

        return $text;
    }
}
