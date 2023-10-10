<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

class ScreenHostingBackups extends Singleton
{
    use ScreenTrait;

    /**
     * Список действий категории Бэкапы
     *
     * @param  string $account_name
     * @return array|string
     */
    public function manage_backups(string $account_name, bool $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран управления резервными копиями.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран со списком путей до сайта от корня. Не относится напрямую к бэкапам! 
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function site_home_paths_list(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка путей сайтов.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Список резервных файловых копий
     *
     * @param  string $account_name
     * @return array|string
     */
    public function files_backup_list(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка резервных файловых копий.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Список резервных копий MySQL
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function mysqls_backup_list(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка резервных копий баз MySQL.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Список файлов и директорий из резервной копии по заданному пути и идентификатору
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function files_list_by_path_and_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка файлов и директорий по заданному пути и идентификатору бэкапа.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Для получения списка впишите ID бэкапа, пробел и путь от корня домашней директории. ";
        $text .= "Также можно через пробел указать число строк для пропуска.";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 /site.ru/public_html 20</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран списка БД из резервной копии по заданному идентификатору.
     * Если идентификатор не задан - значит листинг идет по текущей копии.
     * 
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function mysqls_list_by_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка БД из резервной копии по заданному идентификатору.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Впишите ID копии БД. Если идентификатор не задан - значит листинг идет по текущей копии. Выдача в алфавитном порядке.";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 10</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран заявки на восстановление файлов из резервной копии по заданному пути и резервной копии
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function restore_files_by_path_and_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран заявки на восстановление файлов из резервной копии по заданному идентификатору и пути.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Укажите ID бэкапа, пробел, а далее один или несколько путей от корня через пробел. ";
        $text .= "ID бэкапа - 9 мин. цифр, путь - мин. 10 символов. ";
        $text .= "Не удаляет/не заменяет файлы (02.09.23) если они появились после создания бэкапа и такого файла не было в бэкапе. ";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 /site.ru/public_html /site2.ru/public_html</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран заявки на восстановление БД из резервной копии по заданному имени БД и идентификатору резервной копии
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function restore_mysql_by_db_and_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран заявки на восстановление БД из резервной копии по заданному идентификатору и имени БД.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Укажите ID бэкапа, пробел, а далее одно имя БД или несколько имен БД через пробел. ";
        $text .= "ID бэкапа - 9 мин. цифр, имя БД - мин. 7 символов. ";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 bd_name_1 bd_name_2</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран заявки на выгрузку файлов и выкладывание в корень аккаунта из резервной копии по заданному пути и резервной копии
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function downld_files_by_path_and_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран заявки на выкладывание файлов из резервной копии по заданному идентификатору и пути.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Укажите ID бэкапа, пробел, а далее один или несколько путей от корня через пробел. ";
        $text .= "ID бэкапа - мин. 9 цифр, путь - мин. 10 символов. ";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 /site.ru/public_html /site2.ru/public_html</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран заявки на выкладку в корень аккаунта БД из резервной копии по заданному имени БД и идентификатору резервной копии
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function downld_mysql_by_db_and_id(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран заявки на выкладку в корень аккаунта БД из резервной копии по заданному идентификатору и имени БД.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= "Укажите ID бэкапа, пробел, а далее одно имя БД или несколько имен БД через пробел. ";
        $text .= "ID бэкапа - мин. 9 цифр, имя БД - мин. 7 символов. ";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>\n";
        $text .= "<code>919191919 bd_name_1 bd_name_2</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран со статусами заданий
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public  function backup_log(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран статусов заданий по восстановлению и загрузке.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->backups()->case__backup_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }
}
