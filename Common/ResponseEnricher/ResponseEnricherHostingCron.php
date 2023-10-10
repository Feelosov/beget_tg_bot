<?php

namespace dev_bots_ru\Common\ResponseEnricher;

use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Senders\Beget_hosting;

class ResponseEnricherHostingCron extends Singleton
{
    /**
     * Формирование справки по Cron
     *
     * @return string
     */
    public function get_help(): string
    {
        $text = '';

        $text .= "<b><u>Примеры настройки</u></b>\n\n";
        $text .= "<code>*/5 * * * *</code> — запускать команду каждые пять минут\n\n";
        $text .= "<code>0 */3 * * *</code> — запускать каждые три часа\n\n";
        $text .= "<code>0 12-16 * * *</code> — запускать команду каждый час с 12 до 16 (в 12, 13, 14, 15 и 16)\n\n";
        $text .= "<code>0 12,16,18 * * *</code> — запускать команду каждый час в 12, 16 и 18 часов\n\n";
        $text .= "<code>*/1 * * * * /usr/bin/php ~/site.ru/public_html/test.php</code> — запуск каждую минуту php-скрипта <code>test.php</code>\n\n";
        $text .= "<code>0 */1 * * * /usr/bin/perl ~/site.ru/public_html/test.pl</code> — запуск каждый час perl-скрипта <code>test.pl</code>\n\n";
        $text .= "<b><u>Команда</u></b>\n\n";
        $text .= "Необходимо задать путь до скрипта от домашнего каталога. Например: <code>public_html/cgi-bin/script.pl</code>.\n\n";
        $text .= "Система сама подставит символ ~/ (эта комбинация заменяет полный путь). Получится: <code>~/public_html/cgi-bin/script.pl</code>.\n\n";
        $text .= "Если в конце пути поставить символ & (амперсанд), то скрипт будет работать в фоновом режиме. Установка этого символа необязательна.\n\n";
        $text .= "<b><u>Обратите внимание!</u></b>\n\n";
        $text .= "В ряде случаев требуется выбрать версию PHP отдельно: <code>/usr/local/bin/phpX.X или /usr/local/php-cgi/X.X/bin/php-script</code>. Если для сайта установлена иная версия PHP, следует указать вместо <code>X.X</code> требуемую версию, например, <code>5.6</code>, или актуальную для решения текущей задачи. Версия PHP, установленная для сайта (через раздел Сайты в панели управления), не учитывается при запуске РНР скриптов через Crontab или терминал.\n";
        $text .= "Директивы PHP следует указывать отдельно, после флага -d. Например: /usr/local/bin/php5.6 -d display_errors=1 script.php";
        $text .= "\n\n";
        $text .= "Описание полное: https://beget.com/ru/kb/manual/crontab";
        $text .= "\n";

        return $text;
    }

    /**
     * Возвращает список всех задач Cron
     *
     * @param  string $account
     * @return string
     */
    public function get_list(string $account, int $skip = 0): string
    {
        $text = '';

        $result = Beget_hosting::cronGetList($account);

        $text .= "<u>Результат:</u>\n\n";

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = array_reverse($result['answer']['result']);
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $num => $cron) :
                $status = $cron['is_hidden'] ? '-' : '+';;
                $text .= "<b>Расписание <code>" . $cron['row_number'] . "</code> (" . $status . "):</b>\n";
                $text .= "<code>";
                $text .= $cron['minutes'] . " ";
                $text .= $cron['hours'] . " ";
                $text .= $cron['days'] . " ";
                $text .= $cron['months'] . " ";
                $text .= $cron['weekdays'] . " ";
                $text .= "</code>";
                $text .= "\n";
                $text .= "<b>Команда:</b>\n";
                $text .= "<code>" . $cron['command'] . "</code>";
                $text .= "\n\n";
                $count++;
                if (strlen($text) > 2000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте цифру - число записей, которые нужно пропустить.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано " . $count . " знач. Пропущено " . $skip . ". Всего: " . $array_length . ".</b>\n";
        else :
            $text .= "Задачи Cron не обнаружены";
        endif;
        return $text;
    }

    /**
     * Обработка процесса создания нового задания
     *
     * @param  string $account
     * @param  string $task
     * @return string
     */
    public function add(string $account, string $task = ''): string
    {
        $text = '';

        $pattern = '/^(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)(.+)$/';
        if ($task && preg_match($pattern, $task, $matches)) :
            $minutes = $matches[1] ?? false;
            $hours = $matches[2] ?? false;
            $monthdays = $matches[3] ?? false;
            $months = $matches[4] ?? false;
            $weekdays = $matches[5] ?? false;
            $command = trim($matches[6]) ?? false;
            $result = Beget_hosting::cronAdd($account, $minutes, $hours, $monthdays, $months, $weekdays, $command);
            $text .= "\n\n<b>Результат:</b>\n";
            if (
                $result['answer']['status'] == 'success'
                && isset($result['answer']['result'])
                && $result['answer']['result']
            ) :
                $task_number = $result['answer']['result']['row_number'];
                $text .= "✅ Задача под номером <code>" . $task_number . "</code> поставлена";
            else :
                $text .= "❌ Задача не поставлена. Проверьте корректность расписания и команду. Ошибка:";
                $text .= "\n\n";
                $text .= "<code>" . print_r($result['answer']['errors'], 1) . "</code>";
            endif;
        elseif ($task && !preg_match($pattern, $task, $matches)) :
            $text .= "<b>Результат:</b>\n";
            $text .= "❌ Задача не поставлена. Проверьте корректность расписания и команду.";
        endif;
        return $text;
    }

    /**
     * Обработка процесса изменения задания
     *
     * @param  string $account
     * @param  string $task
     * @return string
     */
    public function edit(string $account, string $task = ''): string
    {
        $text = '';

        $pattern = '/^(\d)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)(.+)$/';
        if ($task && preg_match($pattern, trim($task), $matches)) :
            $status = !$matches[1] ?? false;
            $id = $matches[2] ?? false;
            $minutes = $matches[3] ?? false;
            $hours = $matches[4] ?? false;
            $monthdays = $matches[5] ?? false;
            $months = $matches[6] ?? false;
            $weekdays = $matches[7] ?? false;
            $command = trim($matches[8]) ?? false;
            $result = Beget_hosting::cronEdit($account, $id, $minutes, $hours, $monthdays, $months, $weekdays, $command);
            $result_status = Beget_hosting::cronChangeHiddenState($account, $id, $status);
            $text .= "\n\n<b>Результат:</b>\n";
            if (
                $result['answer']['status'] == 'success'
                && isset($result['answer']['result'])
                && $result['answer']['result']
            ) :
                if (!isset($result_status['row_number'])) :
                    $text_on_status_change = "Изменить статус не удалось.";
                endif;
                $task_number = $result['answer']['result']['row_number'];
                $text .= "✅ Задача под номером <code>" . $task_number . "</code> изменена. " . $text_on_status_change;
            else :
                $text .= "❌ Задача не изменена. Проверьте корректность расписания и команду. Ошибка:";
                $text .= "\n\n";
                $text .= "<code>" . print_r($result['answer']['errors'], 1) . "</code>";
            endif;
        elseif ($task && !preg_match($pattern, $task, $matches)) :
            $text .= "\n\n<b>Результат:</b>\n";
            $text .= "❌ Задача не изменена. Проверьте корректность расписания и команду.";
        endif;

        return $text;
    }

    /**
     * Обрабатывает запрос удаления Cron-задачи
     *
     * @param  string  $account
     * @param  string $ids
     * @return string
     */
    public function delete(string $account, string $ids): string
    {
        $text = '';

        $ids_array = explode(" ", $ids);
        foreach ($ids_array as $num => $id) :
            $result = Beget_hosting::cronDelete($account, trim($id));
            if (
                $result['answer']['status'] == 'success'
                && isset($result['answer']['result'])
                && $result['answer']['result']
            ) :
                $text .= "✅ Задача " . $id . " успешно удалена.\n";
            else :
                $text .= "❌ Задачу " . $id . " не удалось удалить.\n";
            endif;
        endforeach;

        return $text;
    }

    /**
     * Обработка запроса на изменение статуса задачи
     *
     * @param  string  $account
     * @param  integer $new_status
     * @param  integer $id
     * @return string
     */
    public function change_status(string $account, string $parameters): string
    {
        $text = '';

        $parameters = explode(" ", $parameters);
        $new_status = !$parameters[0] ?? 0;
        $id = $parameters[1] ?? 0;

        $result = Beget_hosting::cronChangeHiddenState($account, $id, $new_status);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .= "✅ Статус задачи " . $id . " изменен.\n";
        else :
            $text .= "❌ Статус задачи " . $id . " не изменен.\n";
        endif;

        return $text;
    }

    /**
     * Обработка запроса на получение мейла, куда уходит результат выполнения заданий
     *
     * @param  string $account
     * @param  string $email
     * @return string
     */
    public function get_email(string $account): string
    {
        $text = '';

        $result = Beget_hosting::cronGetEmail($account);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .= "Отчетный email: " . $result['answer']['result'];
        else :
            $text .= "Email не установлен или ошибка. Ответ API:";
            $text .= "\n\n";
            $text .= "<code>" . print_r($result['answer'], 1) . "</code>";
        endif;

        return $text;
    }

    /**
     * Обработка запроса на установку мейла, куда уходит результат выполнения заданий
     *
     * @param  string $account
     * @param  string $email
     * @return string
     */
    public function set_email(string $account, string $email): string
    {
        $text = '';

        if ($email == 0) :
            $email = '';
        endif;

        $result = Beget_hosting::cronSetEmail($account, $email);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .= "✅ Email для отчетов установлен.";
        elseif ($email === '') :
            $text .= "✅ Email убран.";
        else :
            $text .= "❌ Email не установлен. Ответ API:";
            $text .= "\n\n";
            $text .= "<code>" . print_r($result['answer'], 1) . "</code>";
        endif;

        return $text;
    }
}
