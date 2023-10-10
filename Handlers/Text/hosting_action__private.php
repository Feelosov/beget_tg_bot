<?php

use dev_bots_ru\Common\ResponseEnricher;
use dev_bots_ru\Common\Screen;
use dev_bots_ru\General\DB;
use dev_bots_ru\General\Errors;
use dev_bots_ru\General\Parser;
use dev_bots_ru\General\Router;
use dev_bots_ru\Senders\TG;

/**
 * Чтоб этот камелКейз в аду горел или в песке растворился
 *
 * @return void
 */
function hosting_action__private()
{
    //
    $account_name = Router::$parameters['login'];
    $action = Router::$parameters['act'];
    $text = trim(Parser::$tg_data['message']['text']);
    $additional_text = '';

    //
    switch ($action):

            // manage_account

        case Screen::_hosting()->account()->toggle_ssh('', '', true):
            preg_match("/(\d)\s?([^\s]*)?/", $text, $matches);
            $status = $matches[1];
            $ftplogin = $matches[2] ?? '';
            $additional_text = ResponseEnricher::_hosting()->account()->toggle_ssh($account_name, $status, $ftplogin);
            Screen::_hosting()->account()->toggle_ssh($account_name, $additional_text);
            break;

            // manage_backups 

        case Screen::_hosting()->backups()->site_home_paths_list('', '', true):
            preg_match("/(\d){1,}/", $text, $matches);
            $skip = $matches[1] ?? 0;
            $additional_text = ResponseEnricher::_hosting()->backups()->site_home_paths_list($account_name, $skip);
            Screen::_hosting()->backups()->site_home_paths_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->files_backup_list('', '', true):
            preg_match("/(\d){1,}/", $text, $matches);
            $skip = $matches[1] ?? 0;
            $additional_text .= ResponseEnricher::_hosting()->backups()->files_backup_list($account_name, $skip);
            Screen::_hosting()->backups()->files_backup_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->mysqls_backup_list('', '', true):
            preg_match("/(\d){1,}/", $text, $matches);
            $skip = $matches[1] ?? 0;
            $additional_text .= ResponseEnricher::_hosting()->backups()->mysqls_backup_list($account_name, $skip);
            Screen::_hosting()->backups()->mysqls_backup_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->files_list_by_path_and_id('', '', true):
            preg_match("/(\d{9,})\s([^\s]{10,})\s?(\d{1,})?/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $path = $matches[2] ?? '';
            $skip = $matches[3] ?? 0;
            if ($backup_id && $path) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->files_list_by_path_and_id($account_name, $backup_id, $path, $skip);
            else :
                $additional_text .= "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->files_list_by_path_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->mysqls_list_by_id('', '', true):
            preg_match("/(\d{9,})\s?(\d{1,})?/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $skip = $matches[2] ?? 0;
            if ($backup_id) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->mysqls_list_by_id($account_name, $backup_id, $skip);
            else :
                $additional_text .= "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->mysqls_list_by_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->restore_files_by_path_and_id('', '', true):
            preg_match("/(\d{9,})\s(.{10,})/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $paths = explode(" ", $matches[2]) ?? [];
            $paths = array_filter($paths, function ($path) {
                return strlen($path) > 7;
            });
            sort($paths);
            foreach ($paths as $key => $path) :
                $paths[$key] = rtrim($path, '/') . '/';
            endforeach;
            if ($backup_id && $paths) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->restore_files_by_path_and_id($account_name, $backup_id, $paths);
            else :
                $additional_text = "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->restore_files_by_path_and_id($account_name, $additional_text);
            // Здесь для безопасности лучше вставить очистку последнего действия, чтобы пользователь не запутался.
            Screen::$text .= "\n\n<b>Последнее действие очищено для безопасности. Чтобы восстановить новую партию данных, нажмите еще раз кнопку восстановления.</b>\n";
            DB::clear__current_user_action();
            break;

        case Screen::_hosting()->backups()->restore_mysql_by_db_and_id('', '', true):
            preg_match("/(\d{9,})\s(.{7,})/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $bd_names = explode(" ", $matches[2]) ?? [];
            if ($backup_id && $bd_names) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->restore_mysql_by_db_and_id($account_name, $backup_id, $bd_names);
            else :
                $additional_text = "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->restore_mysql_by_db_and_id($account_name, $additional_text);
            // Здесь для безопасности лучше вставить очистку последнего действия, чтобы пользователь не запутался.
            Screen::$text .= "\n\n<b>Последнее действие очищено для безопасности. Чтобы восстановить новую партию данных, нажмите еще раз кнопку восстановления.</b>\n";
            DB::clear__current_user_action();
            break;

        case Screen::_hosting()->backups()->downld_files_by_path_and_id('', '', true):
            preg_match("/(\d{9,})\s(.{10,})/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $paths = explode(" ", $matches[2]) ?? [];
            $paths = array_filter($paths, function ($path) {
                return strlen($path) > 7;
            });
            sort($paths);
            foreach ($paths as $key => $path) :
                $paths[$key] = rtrim($path, '/') . '/';
            endforeach;
            if ($backup_id && $paths) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->downld_files_by_path_and_id($account_name, $backup_id, $paths);
            else :
                $additional_text = "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->downld_files_by_path_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->downld_mysql_by_db_and_id('', '', true):
            preg_match("/(\d{9,})\s(.{7,})/", $text, $matches);
            $backup_id = $matches[1] ?? 0;
            $bd_names = explode(" ", $matches[2]) ?? [];
            if ($backup_id && $bd_names) :
                $additional_text .= ResponseEnricher::_hosting()->backups()->downld_mysql_by_db_and_id($account_name, $backup_id, $bd_names);
            else :
                $additional_text = "⛔️ Некорректные вводные данные.\n";
            endif;
            Screen::_hosting()->backups()->downld_mysql_by_db_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->backup_log('', '', true):
            preg_match("/(\d{1,})?/", $text, $matches);
            $skip = $matches[1] ?? 0;
            $additional_text .= ResponseEnricher::_hosting()->backups()->backup_log($account_name, $skip);
            Screen::_hosting()->backups()->backup_log($account_name, $additional_text);
            break;

            // manage_cron

        case Screen::_hosting()->cron()->get_list('', '', true):
            preg_match("/(\d{1,})?/", $text, $matches);
            $skip = $matches[1] ?? 0;
            $additional_text .= ResponseEnricher::_hosting()->cron()->get_list($account_name, $skip);
            Screen::_hosting()->cron()->get_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->add('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->add($account_name, $text);
            Screen::_hosting()->cron()->add($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->edit('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->edit($account_name, $text);
            Screen::_hosting()->cron()->edit($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->delete('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->delete($account_name, $text);
            Screen::_hosting()->cron()->delete($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->change_status('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->change_status($account_name, $text);
            Screen::_hosting()->cron()->change_status($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->set_email('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->set_email($account_name, $text);
            Screen::_hosting()->cron()->set_email($account_name, $additional_text);
            break;

            // Действие не назначено

        default:
            Screen::_hosting()->categories($account_name);
            Screen::$text .= "\n\n";
            Screen::$text .= "<u>Выбранное действие недоступно</u>";
            break;

    endswitch;

    // TODO: Очистим действия ? Дело в том, что экран манит ввести повторно данные, а если очистить, то будет вызов неизвестной команды.
    // DB::clear__current_user_action();

    //
    if (Screen::$text) :
        $current_action = trim(DB::get__user_action());
        if ($current_action) :
            Screen::$text .= "\n\n\nТекущее действие: <code>" . $action . "</code>";
        else :
            Screen::$text .= "\n\n\nТекущее действие: ⚠️<code>сброшено</code>";
        endif;
        TG::sendMessage(Parser::$tg_user_id, Screen::$text, Screen::$buttons);
    else :
        Errors::add__error_message(
            "Пустая текстовая часть при попытке отправить сообщение: " . $action,
            __FILE__,
            __LINE__
        );
        Errors::send__error_message();
        exit;
    endif;
}
