<?php

namespace dev_bots_ru\Senders;

use dev_bots_ru\Common\Encryption;
use dev_bots_ru\General\Config;
use dev_bots_ru\General\DB;
use dev_bots_ru\General\Parser;

class Beget_hosting
{

    /***
     *    ..####....####....####..
     *    .##..##..##..##..##..##.
     *    .######..##......##.....
     *    .##..##..##..##..##..##.
     *    .##..##...####....####..
     */

    /**
     * Метод возвращает информацию о тарифном плане пользователя, 
     * о некоторых параметрах сервера, на котором пользователь 
     * размещается в данный момент, и используемых лимитах на нем.
     *
     * @param  string $account
     * @return array
     */
    public static function userGetAccountInfo(string $account): array
    {
        $scope = 'user';
        $method = 'getAccountInfo';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод включает или выключает SSH, если нет дополнительного параметра 
     * ftplogin - для основного аккаунта, с ftplogin - для указанного ftp аккаунта.
     *
     * @param  string  $account
     * @param  integer $status
     * @param  string  $ftplogin
     * @return array
     */
    public static function userToggleSsh(string $account, int $status, string $ftplogin = ''): array
    {
        $scope = 'user';
        $method = 'toggleSsh';

        $send_data = [
            'status' => $status,
        ];

        if ($ftplogin) :
            $send_data['ftplogin'] = $ftplogin;
        endif;

        return self::sender($account, $scope, $method, $send_data);
    }

    /***
     *    .#####....####....####...##..##..##..##..#####..
     *    .##..##..##..##..##..##..##.##...##..##..##..##.
     *    .#####...######..##......####....##..##..#####..
     *    .##..##..##..##..##..##..##.##...##..##..##.....
     *    .#####...##..##...####...##..##...####...##.....
     */

    /**
     * Метод возвращает доступный список резервных файловых копий.
     *
     * @param  string $account
     * @return array
     */
    public static function backupGetFileBackupList(string $account): array
    {
        $scope = 'backup';
        $method = 'getFileBackupList';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает доступный список резервных копий MySQL.
     *
     * @param  string $account
     * @return array
     */
    public static function backupGetMysqlBackupList(string $account): array
    {
        $scope = 'backup';
        $method = 'getMysqlBackupList';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает список файлов и директорий из резервной копии по заданному пути и идентификатору.
     *
     * @param  string $account
     * @return array
     */
    public static function backupGetFileList(string $account, int $backup_id, string $path): array
    {
        $scope = 'backup';
        $method = 'getFileList';

        $send_data = [
            'backup_id' => $backup_id,
            'path' => $path
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает список баз данных из резервной копии по заданному идентификатору.
     *
     * @param  string  $account
     * @param  integer $backup_id если не задан - значит листинг идет по текущей копии.
     * @return array
     */
    public static function backupGetMysqlList(string $account, int $backup_id = 0): array
    {
        $scope = 'backup';
        $method = 'getMysqlList';

        $send_data = [];
        if ($backup_id) :
            $send_data['backup_id'] = $backup_id;
        endif;

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод создает заявку на восстановление файлов из резервной копии по заданному пути и резервной копии.
     *
     * @param  string  $account
     * @param  integer $backup_id
     * @param  string  $path
     * @return array
     */
    public static function backupRestoreFile(string $account, int $backup_id, array $paths): array
    {
        $scope = 'backup';
        $method = 'restoreFile';

        $send_data = [
            'backup_id' => $backup_id,
            'paths' => $paths
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод создает заявку на восстановление БД из резервной копии по заданному имени БД и идентификатору резервной копии.
     *
     * @param  string  $account
     * @param  integer $backup_id
     * @param  array   $bases
     * @return array
     */
    public static function backupRestoreMysql(string $account, int $backup_id, array $bases): array
    {
        $scope = 'backup';
        $method = 'restoreMysql';

        $send_data = [
            'backup_id' => $backup_id,
            'bases' => $bases
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод создает заявку на загрузку и выкладывание данных из резервной копии в корень аккаунта.
     *
     * @param  string  $account
     * @param  integer $backup_id
     * @param  array   $paths
     * @return array
     */
    public static function backupDownloadFile(string $account, int $backup_id, array $paths): array
    {
        $scope = 'backup';
        $method = 'downloadFile';

        $send_data = [
            'backup_id' => $backup_id,
            'paths' => $paths
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод создает заявку на загрузку и выкладывание данных из резервной копии в корень аккаунта.
     *
     * @param  string  $account
     * @param  integer $backup_id
     * @param  array   $bases
     * @return array
     */
    public static function backupDownloadMysql(string $account, int $backup_id, array $bases): array
    {
        $scope = 'backup';
        $method = 'downloadMysql';

        $send_data = [
            'backup_id' => $backup_id,
            'bases' => $bases
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает список и статусы заданий по восстановлению и загрузке.
     *
     * @param  string $account
     * @return array
     */
    public static function backupGetLog(string $account): array
    {
        $scope = 'backup';
        $method = 'getLog';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }


    /***
     *    ..####...#####....####...##..##.
     *    .##..##..##..##..##..##..###.##.
     *    .##......#####...##..##..##.###.
     *    .##..##..##..##..##..##..##..##.
     *    ..####...##..##...####...##..##.
     *    ................................
     */

    public static function cronGetList(string $account): array
    {
        $scope = 'cron';
        $method = 'getList';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод добавит новое задание. После добавления задание будет активно.
     *
     * @param  string $account
     * @param  string $minutes
     * @param  string $hours
     * @param  string $days
     * @param  string $months
     * @param  string $weekdays
     * @param  string $command
     * @return array
     */
    public static function cronAdd(
        string $account,
        string $minutes,
        string $hours,
        string $days,
        string $months,
        string $weekdays,
        string $command
    ): array {

        $scope = 'cron';
        $method = 'add';

        $send_data = [
            'minutes' => $minutes,
            'hours' => $hours,
            'days' => $days,
            'months' => $months,
            'weekdays' => $weekdays,
            'command' => $command,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод изменит указанное задание.
     *
     * @param  string  $account
     * @param  integer $id
     * @param  string  $minutes
     * @param  string  $hours
     * @param  string  $days
     * @param  string  $months
     * @param  string  $weekdays
     * @param  string  $command
     * @return array
     */
    public static function cronEdit(
        string $account,
        int $id,
        string $minutes,
        string $hours,
        string $days,
        string $months,
        string $weekdays,
        string $command
    ): array {

        $scope = 'cron';
        $method = 'edit';

        $send_data = [
            'id' => $id,
            'minutes' => $minutes,
            'hours' => $hours,
            'days' => $days,
            'months' => $months,
            'weekdays' => $weekdays,
            'command' => $command,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод удалит задание с указанным ID.
     *
     * @param  string  $account
     * @param  integer $id
     * @return array
     */
    public static function cronDelete(string $account, int $id): array
    {
        $scope = 'cron';
        $method = 'delete';

        $send_data = [
            'row_number' => $id,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод изменит статус задания.
     *
     * @param  string  $account
     * @param  integer $id
     * @param  integer $is_hidden
     * @return array
     */
    public static function cronChangeHiddenState(string $account, int $id, bool $is_hidden): array
    {
        $scope = 'cron';
        $method = 'changeHiddenState';

        $send_data = [
            'row_number' => $id,
            'is_hidden' => $is_hidden,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает email, на который приходит вывод выполненных заданий.
     *
     * @param  string $account
     * @return array
     */
    public static function cronGetEmail(string $account): array
    {
        $scope = 'cron';
        $method = 'getEmail';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод устанавливает email, на который приходит вывод выполненных заданий.
     *
     * @param  string $account
     * @param  string $email
     * @return array
     */
    public static function cronSetEmail(string $account, string $email): array
    {
        $scope = 'cron';
        $method = 'setEmail';

        $send_data = [
            'email' => $email,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /***
     *    ..####...######..######..######...####..
     *    .##........##......##....##......##.....
     *    ..####.....##......##....####.....####..
     *    .....##....##......##....##..........##.
     *    ..####...######....##....######...####..
     */

    /**
     * Метод возвращает список сайтов. Если к сайту прилинкованы домены, то они так же будут возвращены.
     *
     * @param  string $account
     * @return array
     */
    public static function siteGetList(string $account): array
    {
        $scope = 'site';
        $method = 'getList';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /***
     *    .######..######..#####..
     *    .##........##....##..##.
     *    .####......##....#####..
     *    .##........##....##.....
     *    .##........##....##.....
     */

    /**
     * Метод возвращает список дополнительных FTP-аккаунтов с их домашними директориями.
     *
     * @param  string $account
     * @return array
     */
    public static function ftpGetFTPList(string $account): array
    {
        $scope = 'ftp';
        $method = 'getList';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /***
     *    ..####...######...####...######.
     *    .##........##....##..##....##...
     *    ..####.....##....######....##...
     *    .....##....##....##..##....##...
     *    ..####.....##....##..##....##...
     *    ................................
     */

    /**
     * Метод возвращает информацию о средней нагрузке на 
     * сайтах пользователя за последний месяц.
     *
     * @param  string $account
     * @return array
     */
    public static function statGetSiteListLoad(string $account): array
    {
        $scope = 'stat';
        $method = 'getSitesListLoad';

        $send_data = [];

        return self::sender($account, $scope, $method, $send_data);
    }

    /**
     * Метод возвращает детальную информацию о нагрузке на указаном сайте (нагрузка по дням и часам).
     *
     * @param  string  $account
     * @param  integer $site_id
     * @return array
     */
    public static function statGetSiteLoad(string $account, int $site_id): array
    {
        $scope = 'stat';
        $method = 'getSiteLoad';

        $send_data = [
            'site_id' => $site_id,
        ];

        return self::sender($account, $scope, $method, $send_data);
    }

    /***
     *    ..####...######..##..##..#####..
     *    .##......##......###.##..##..##.
     *    ..####...####....##.###..##..##.
     *    .....##..##......##..##..##..##.
     *    ..####...######..##..##..#####..
     */

    /**
     * Отправка запроса в Бегет
     *
     * @param  string $account
     * @param  string $scope
     * @param  string $method
     * @param  array  $send_data
     * @return array
     */
    private static function sender(string $account, string $scope, string $method, array $send_data): array
    {
        // Покажем действие в боте
        $tg_user_id = Parser::$tg_user_id ?? DB::get__bot_owner_tg_id();
        TG::sendChatAction($tg_user_id);

        $account_password = DB::get__encrypted_password($account);
        $account_iv       = DB::get__encrypted_iv($account);
        $account_tag      = DB::get__encrypted_tag($account);
        $account_ad       = DB::get__encrypted_ad($account);
        $decrypted_password = Encryption::decrypt_data($account_password, $account_iv, $account_tag, $account_ad);

        $url = 'https://api.beget.com/api/' . $scope . '/' . $method . '?login=' . $account . '&passwd=' . urlencode($decrypted_password) . '&input_format=json&output_format=json&input_data=' . urlencode(json_encode($send_data));

        /**
         * Инициализация запроса в Бегет
         */
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $send_data,
                CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
            ]
        );

        /**
         * Результат запроса из Бегета
         */
        $result = curl_exec($curl);
        $result = json_decode($result, 1);
        curl_close($curl);

        if ($result == false || $result['status'] == 'error') :
            $result['date_time'] = date('Y-m-d H:i:s');
            file_put_contents(Config::get__bot_root_dir() . '/__errors_beget_hosting.log', print_r($result, 1) . "\n\n", FILE_APPEND);
        endif;

        return $result;
    }
}
