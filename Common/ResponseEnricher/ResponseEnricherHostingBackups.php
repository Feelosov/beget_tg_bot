<?php

namespace dev_bots_ru\Common\ResponseEnricher;

use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\Parser;
use dev_bots_ru\Senders\Beget_hosting;
use dev_bots_ru\Senders\TG;

class ResponseEnricherHostingBackups extends Singleton
{
    /**
     * Список корневых путей сайтов
     *
     * @param  string $account_name
     * @return string
     */
    public function site_home_paths_list(string $account_name, int $skip = 0): string
    {
        $text = '';

        $result = Beget_hosting::siteGetList($account_name);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $data) :
                $text .= "<code>/" . str_replace("\\", "", $data['path']) . "/</code>\n\n";
                $count++;
                if (strlen($text) > 1000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте цифру - число строк, которые нужно пропустить.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано: " . $count . " знач. Пропущено: " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;

        return $text;
    }

    /**
     * Получаем список доступных резервных файловых копий
     *
     * @param  string $account_name
     * @return string
     */
    public function files_backup_list(string $account_name, int $skip = 0): string
    {
        $text = '';
        $result = Beget_hosting::backupGetFileBackupList($account_name);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $backup_data) :
                $text .= "<code>" . $backup_data['backup_id'] . "</code> | " . $backup_data['date'] . "\n";
                $count++;
                if (strlen($text) > 1000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте цифру - число строк, которые нужно пропустить.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано: " . $count . " знач. Пропущено: " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;
        return $text;
    }

    /**
     * Получаем список доступных резервных MySQL
     *
     * @param  string $account_name
     * @return string
     */
    public function mysqls_backup_list(string $account_name, int $skip = 0): string
    {
        $text = '';
        $result = Beget_hosting::backupGetMysqlBackupList($account_name);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $backup_data) :
                $text .= "<code>" . $backup_data['backup_id'] . "</code> | " . $backup_data['date'] . "\n";
                $count++;
                if (strlen($text) > 1000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте цифру - число строк, которые нужно пропустить.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано: " . $count . " знач. Пропущено: " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;
        return $text;
    }

    /**
     * Получаем список доступных резервных MySQL
     *
     * @param  string $account_name
     * @return string
     */
    public function files_list_by_path_and_id(string $account_name, int $backup_id, string $path, int $skip = 0): string
    {
        $text = '';

        // TODO:
        $result = Beget_hosting::backupGetFileList($account_name, $backup_id, $path);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $backup_data) :
                $is_dir = $backup_data['is_dir'] ? ' директория ' : ' файл ';
                $text .= $backup_data['mtime'] . " | " . $is_dir . " | " . $backup_data['name']  . " | " . $backup_data['size'] . " bytes\n\n";
                $count++;
                if (strlen($text) > 2000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте ту же команду, но с цифрой - число строк, которые нужно пропустить.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано " . $count . " знач. Пропущено " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;
        return $text;
    }

    /**
     * Выдает список баз данных из резервной копии по заданному идентификатору
     *
     * @param  string  $account_name
     * @param  integer $backup_id
     * @return string
     */
    public function mysqls_list_by_id(string $account_name, int $backup_id = 0, int $skip = 0): string
    {
        $text = '';

        // TODO:
        $result = Beget_hosting::backupGetMysqlList($account_name, $backup_id);
        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            sort($result);
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $backup_data) :
                $text .= "<code>" . $backup_data . "</code>\n";
                $count++;
                if (strlen($text) > 1000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте тот же ID, пробел и цифру - число строк, которые нужно пропустить. Если не хотите указывать ID бэкапа, то впишите девять нулей <code>000000000</code> и потом количество строк для пропуска.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано " . $count . " знач. Пропущено " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;
        return $text;
    }

    /**
     * Обработка заявки на восстановление данных из резервной копии по заданному пути и резервной копии.
     *
     * @param  string  $account_name
     * @param  integer $backup_id
     * @param  string  $path
     * @return string
     */
    public function restore_files_by_path_and_id(string $account_name, int $backup_id, array $paths): string
    {
        $text = '';
        // TODO:
        $result = Beget_hosting::backupRestoreFile($account_name, $backup_id, $paths);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .=  "✅ успешно. Ожидайте уведомления.\n";
        else :
            $text .=  "❌ не успешно:\n\n<pre>" . print_r($result['answer']['errors'], 1) . "</pre>";
        endif;
        return $text;
    }

    /**
     * Обработка заявки на восстановление данных из резервной копии по заданному имени и резервной копии.
     *
     * @param  string  $account_name
     * @param  integer $backup_id
     * @param  array   $db_names
     * @return string
     */
    public function restore_mysql_by_db_and_id(string $account_name, int $backup_id, array $db_names): string
    {
        $text = '';
        // TODO:
        $result = Beget_hosting::backupRestoreMysql($account_name, $backup_id, $db_names);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .=  "✅ успешно. Ожидайте уведомления.\n";
        else :
            $text .=  "❌ не успешно:\n\n<pre>" . print_r($result['answer']['errors'], 1) . "</pre>";
        endif;
        return $text;
    }

    /**
     * Обработка заявки на выкладку данных в корень аккаунта из резервной копии по заданному пути и резервной копии.
     *
     * @param  string  $account_name
     * @param  integer $backup_id
     * @param  string  $path
     * @return string
     */
    public function downld_files_by_path_and_id(string $account_name, int $backup_id, array $paths): string
    {
        $text = '';
        // TODO:
        $result = Beget_hosting::backupDownloadFile($account_name, $backup_id, $paths);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .=  "✅ успешно. Ожидайте уведомления.\n";
        else :
            $text .=  "❌ не успешно:\n\n<pre>" . print_r($result['answer']['errors'], 1) . "</pre>";
        endif;
        return $text;
    }

    /**
     * Обработка заявки на выкладку данных в корень акканута данных из резервной копии по заданному имени и резервной копии.
     *
     * @param  string  $account_name
     * @param  integer $backup_id
     * @param  array   $db_names
     * @return string
     */
    public function downld_mysql_by_db_and_id(string $account_name, int $backup_id, array $db_names): string
    {
        $text = '';
        // TODO:
        $result = Beget_hosting::backupDownloadMysql($account_name, $backup_id, $db_names);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $text .=  "✅ успешно. Ожидайте уведомления.\n";
        else :
            $text .=  "❌ не успешно:\n\n<pre>" . print_r($result['answer']['errors'], 1) . "</pre>";
        endif;
        return $text;
    }

    /**
     * Возвращает данные со статусами заданий
     *
     * @param  string $account_name
     * @return string
     */
    public function backup_log(string $account_name, int $skip = 0): string
    {
        $text = '';
        // TODO:
        $result = Beget_hosting::backupGetLog($account_name);

        if (
            $result['answer']['status'] == 'success'
            && isset($result['answer']['result'])
            && $result['answer']['result']
        ) :
            $result = $result['answer']['result'];
            $array_length = count($result);
            $result = array_slice($result, $skip);
            $count = 0;
            foreach ($result as $task) :
                $text .= "<b>ID:</b> <code>" . $task['id'] . "</code>\n";
                $text .= "<b>Тип запроса:</b> " . $task['operation'] . "\n";
                $text .= "<b>Тип данных:</b> " . $task['type'] . "\n";
                $text .= "<b>Дата заявки:</b> " . $task['date_create'] . "\n";
                $text .= "<b>Данные:</b>\n";
                foreach ($task['target_list'] as $target) :
                    $text .= "> <code>" . $target . "</code>\n";
                endforeach;
                $text .= "<b>Статус успеха:</b> " . $task['status'] . "\n\n";
                $count++;
                if (strlen($text) > 1000) :
                    $text .= "\n";
                    $text .= "ℹ️ Не все данные попали из-за ограничения в ТГ длины сообщения от бота. ";
                    $text .= "Чтобы отобразить следующий набор данных отправьте тот же ID, пробел и цифру - число строк, которые нужно пропустить. Если не хотите указывать ID бэкапа, то впишите девять нулей <code>000000000</code> и потом количество строк для пропуска.\n";
                    break;
                endif;
            endforeach;
            $text .= "\n<b>Показано " . $count . " знач. Пропущено " . $skip . ". Всего: " . $array_length . ".</b>\n";
        endif;
        return $text;
    }
}
