<?php

namespace dev_bots_ru\Common\ResponseEnricher;

use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Senders\Beget_hosting;

/**
 * Обработчик ответов из области действия "Аккаунт"
 */
class ResponseEnricherHostingAccount extends Singleton
{
    /**
     * Подготовка текста для экрана при запросе информации о тарифе
     *
     * @param  string $account_name
     * @return string
     */
    public function plan_info(string $account_name): string
    {
        $text = '';

        $result = Beget_hosting::userGetAccountInfo($account_name);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result']['plan_name'])
            && $result['answer']['result']['plan_name']
        ) :
            $result = $result['answer']['result'];
            foreach ($result as $key => $parameter) :
                if ($parameter == 2147483647) :
                    $result[$key] = "∞";
                endif;
            endforeach;
            if ($result['user_rate_current'] > $result['user_balance'] * 3) :
                $notify_sign = ' ⬅️';
            endif;
            $text .= "- Текущий баланс: <b>" . $result['user_balance'] . $notify_sign . "</b>\n";
            $text .= "- <u>Расход в день</u>: <b>" . $result['user_rate_current'] . "</b>\n";
            $text .= "- Годовая скидка: <b>" . ((int)$result['user_is_year_plan'] ? "нет" : "да") . "</b>\n";
            $text .= "- Стоимость в год: <b>" . number_format($result['user_rate_year'], 0, ".", " ") . "</b>\n";
            $text .= "- Стоимость в месяц: <b>" . $result['user_rate_month'] . "</b>\n";
            $text .= "- Партнерский баланс: <b>нет в API</b>\n\n";

            $text .= "- Название тарифа: <b>" . $result['plan_name'] . "</b>\n";
            $notify_sign = '';
            if ($result['user_sites'] >= ($result['plan_site'] - 1)) :
                $notify_sign = ' ⬅️';
            endif;
            $text .= "- Фактически сайтов: <b>" . $result['user_sites'] . $notify_sign . "</b>\n";
            $text .= "- Предел сайтов: <b>" . $result['plan_site'] . "</b>\n";
            $text .= "- Фактически доменов: <b>" . $result['user_domains'] . "</b>\n";
            $text .= "- Предел доменов: <b>" . $result['plan_domain'] . "</b>\n";
            $text .= "- Фактический размер БД: <b>" . number_format($result['user_mysqlsize'], 0, ".", " ") . "</b>\n";
            $text .= "- Предел размера БД: <b>" . $result['plan_mysql'] . "</b>\n";
            $notify_sign = '';
            if ($result['user_quota'] >= ($result['plan_quota'] - 2000)) :
                $notify_sign = ' ⬅️';
            endif;
            $text .= "- Фактический размера диска: <b>" . number_format($result['user_quota'], 0, ".", " ") .  $notify_sign . "</b>\n";
            $text .= "- Предел размера диска: <b>" . number_format($result['plan_quota'], 0, ".", " ") . "</b>\n";
            $text .= "- Создано FTP: <b>" . $result['user_ftp'] . "</b>\n";
            $text .= "- Предел FTP: <b>" . $result['plan_ftp'] . "</b>\n";
            $text .= "- Создано почт. ящиков: <b>" . $result['user_mail'] . "</b>\n";
            $text .= "- Предел почт. ящиков: <b>" . $result['plan_mail'] . "</b>\n";
            $query_result_stat_site_load = Beget_hosting::statGetSiteListLoad($account_name);
            if (
                $query_result_stat_site_load['answer']['status'] == 'success'
                && isset($query_result_stat_site_load['answer']['result'])
                && $query_result_stat_site_load['answer']['result']
            ) :
                $cp = 0.0;
                foreach ($query_result_stat_site_load['answer']['result'] as $load_data) :
                    $cp += $load_data['cp'];
                endforeach;
                $notify_sign = '';
                if ($cp >= ($result['plan_cp'] * 0.7)) :
                    $notify_sign = ' ⬅️';
                endif;
                $text .= "- <u>Нагрузка CP (средн. мес.)</u>: <b>" . round($cp, 2) . $notify_sign . "</b>\n";
            endif;
            $text .= "- Предел CP: <b>" . $result['plan_cp'] . "</b>\n";


        else :
            $text .= "<u>Нет данных для отображения</u>";
        endif;

        return $text;
    }

    /**
     *  Подготовка текста для экрана при запросе информации о сервере
     *
     * @param  string $account_name
     * @return string
     */
    public function server_info(string $account_name): string
    {
        $text = '';

        $result = Beget_hosting::userGetAccountInfo($account_name);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result']['plan_name'])
            && $result['answer']['result']['plan_name']
        ) :
            $result = $result['answer']['result'];
            $text .= "- Оболочка командная: <b>" . $result['user_bash'] . "</b>\n";
            $text .= "- Версия Apache: <b>" . $result['server_apache_version'] . "</b>\n";
            $text .= "- Версия MySQL: <b>" . $result['server_mysql_version'] . "</b>\n";
            $text .= "- Версия Nginx: <b>" . $result['server_nginx_version'] . "</b>\n";
            $text .= "- Версия Perl: <b>" . $result['server_perl_version'] . "</b>\n";
            $text .= "- Версия PHP: <b>" . $result['server_php_version'] . "</b>\n";
            $text .= "- Версия Python: <b>" . $result['server_python_version'] . "</b>\n";
            $text .= "- Всего ОЗУ: <b>" . number_format($result['server_memory'], 0, ".", " ") . "</b>\n";
            $text .= "- Занято ОЗУ: <b>" . number_format($result['server_memorycurrent'], 0, ".", " ") . "</b>\n";
            $text .= "- Нагрузка текущая: <b>" . round($result['server_loadaverage'], 2) . "</b>\n";
            $text .= "- Время жизни (дн.): <b>" . $result['server_uptime'] . "</b>\n";
            $text .= "- Имя сервера: <b>" . $result['server_name'] . "</b>\n";
            $text .= "- Процессор: <b>" . str_replace("  ", " ", $result['server_cpu_name']) . "</b>\n";
        endif;

        return $text;
    }

    /**
     * Набор существующих FTP-аккаунтов для быстрой подсказки на toggleSsh
     *
     * @param  string $account_name
     * @return string
     */
    public function ftp_accounts(string $account_name): string
    {
        $text = '';

        // TODO:
        $result = Beget_hosting::ftpGetFTPList($account_name);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $text .= "<b>Быстрые подсказки:</b>\n";
            foreach ($result as $ftp_login) :
                $text .= "<code>1 " . $ftp_login['login'] . "</code> | <code>0 " . $ftp_login['login'] . "</code>\n";
                if (strlen($text) > 2000) :
                    $text .= "... не все FTP-аккаунты могли попасть из-за ограничения в ТГ длины сообщения от бота.";
                    break;
                endif;
            endforeach;
        endif;

        return $text;
    }

    /**
     * Обработка введенных данных для toggleSsh и результата запроса
     *
     * @param  string  $account_name
     * @param  integer $status
     * @param  string  $ftplogin
     * @return string
     */
    public function toggle_ssh(string $account_name, int $status, string $ftplogin): string
    {
        $text = '';
        $status_text = $status === 0 ? 'Отключение' : 'Включение';

        $result_toggleSsh = Beget_hosting::userToggleSsh($account_name, $status, $ftplogin);

        $text .= $this->ftp_accounts($account_name);
        $text .= "\n";

        if (
            $result_toggleSsh['status'] == 'success'
            && isset($result_toggleSsh['answer']['status'])
            && $result_toggleSsh['answer']['status'] == 'success'
        ) :
            $text .= "✅ <b>" . $status_text . " прошло успешно!</b>\n";
        else :
            $text .= "❌ <b>" . $status_text . " не произошло!</b>\n";
        endif;

        return $text;
    }
}
