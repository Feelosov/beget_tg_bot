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
    $additional_text = '';

    //
    switch ($action):

            // Категории действий хостинга

        case Screen::_hosting()->categories('', true):
            Screen::_hosting()->categories($account_name);
            break;

            // manage_account

        case Screen::_hosting()->account()->manage_account('', true):
            Screen::_hosting()->account()->manage_account($account_name);
            break;

        case Screen::_hosting()->account()->plan_info('', '', true):
            $additional_text = ResponseEnricher::_hosting()->account()->plan_info($account_name);
            Screen::_hosting()->account()->plan_info($account_name, $additional_text);
            break;

        case Screen::_hosting()->account()->server_info('', '', true):
            $additional_text = ResponseEnricher::_hosting()->account()->server_info($account_name);
            Screen::_hosting()->account()->server_info($account_name, $additional_text);
            break;

        case Screen::_hosting()->account()->toggle_ssh('', '', true):
            $additional_text = ResponseEnricher::_hosting()->account()->ftp_accounts($account_name);
            Screen::_hosting()->account()->toggle_ssh($account_name, $additional_text);
            break;

            // manage_backups 

        case Screen::_hosting()->backups()->manage_backups('', true):
            Screen::_hosting()->backups()->manage_backups($account_name);
            break;

        case Screen::_hosting()->backups()->site_home_paths_list('', '', true):
            $additional_text = ResponseEnricher::_hosting()->backups()->site_home_paths_list($account_name);
            Screen::_hosting()->backups()->site_home_paths_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->files_backup_list('', '', true):
            $additional_text = ResponseEnricher::_hosting()->backups()->files_backup_list($account_name);
            Screen::_hosting()->backups()->files_backup_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->mysqls_backup_list('', '', true):
            $additional_text = ResponseEnricher::_hosting()->backups()->mysqls_backup_list($account_name);
            Screen::_hosting()->backups()->mysqls_backup_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->files_list_by_path_and_id('', '', true):
            Screen::_hosting()->backups()->files_list_by_path_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->mysqls_list_by_id('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->backups()->mysqls_list_by_id($account_name);
            Screen::_hosting()->backups()->mysqls_list_by_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->restore_files_by_path_and_id('', '', true):
            Screen::_hosting()->backups()->restore_files_by_path_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->restore_mysql_by_db_and_id('', '', true):
            Screen::_hosting()->backups()->restore_mysql_by_db_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->downld_files_by_path_and_id('', '', true):
            Screen::_hosting()->backups()->downld_files_by_path_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->downld_mysql_by_db_and_id('', '', true):
            Screen::_hosting()->backups()->downld_mysql_by_db_and_id($account_name, $additional_text);
            break;

        case Screen::_hosting()->backups()->backup_log('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->backups()->backup_log($account_name);
            Screen::_hosting()->backups()->backup_log($account_name, $additional_text);
            break;

            // manage_cron

        case Screen::_hosting()->cron()->manage_cron('', true):
            Screen::_hosting()->cron()->manage_cron($account_name);
            break;

        case Screen::_hosting()->cron()->get_help('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->get_help();
            Screen::_hosting()->cron()->get_help($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->get_list('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->get_list($account_name);
            Screen::_hosting()->cron()->get_list($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->add('', '', true):
            Screen::_hosting()->cron()->add($account_name, $additional_text);
            break;
        case Screen::_hosting()->cron()->edit('', '', true):
            Screen::_hosting()->cron()->edit($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->delete('', '', true):
            $action = '⚠️ ' . $action;
            Screen::_hosting()->cron()->delete($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->change_status('', '', true):
            Screen::_hosting()->cron()->change_status($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->get_email('', '', true):
            $additional_text .= ResponseEnricher::_hosting()->cron()->get_email($account_name);
            Screen::_hosting()->cron()->get_email($account_name, $additional_text);
            break;

        case Screen::_hosting()->cron()->set_email('', '', true):
            Screen::_hosting()->cron()->set_email($account_name, $additional_text);
            break;

            // Действие не назначено

        default:
            Screen::_hosting()->categories($account_name);
            Screen::$text .= "\n\n";
            Screen::$text .= "<u>Выбранное действие недоступно.</u>";
            break;

    endswitch;

    //
    if (Screen::$text) :
        $current_action = trim(DB::get__user_action());
        if ($current_action) :
            Screen::$text .= "\n\n\nТекущее действие: <code>" . $action . "</code>";
        else :
            Screen::$text .= "\n\n\nТекущее действие: <code>не установлено</code>";
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
