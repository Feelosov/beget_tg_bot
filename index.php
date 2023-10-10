<?php

/**
 * PHP 8.2+
 * 
 * Боты от dev-bots.ru.
 * Запрещается передавать третьим лицами, получать доход от продажи всего или части кода.
 * При изменении файлов снимается ответственность с dev-bots.ru
 * © https://dev-bots.ru
 * 
 * Bots by dev-bots.ru.
 * It is prohibited to transfer to third parties, to receive income from the sale of all or part of the code.
 * When changing files, responsibility is removed from dev-bots.ru
 * © https://dev-bots.ru
 * 
 * If you don't know Russian, learn it.
 */

http_response_code(200);

ini_set('error_reporting', 32767); // Все
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('ignore_repeated_errors', 1);

header("Content-Type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Cache-Control: no-cache, must-revalidate");

// Только для безбраузерных запросов
if (strlen($_SERVER['HTTP_USER_AGENT']) > 0) :
    exit('Silent is gold!');
endif;

use dev_bots_ru\Common\Cron;
use dev_bots_ru\General\DB;
use dev_bots_ru\General\Router;
use dev_bots_ru\Senders\TG;

/**
 * Установим часовой пояс по умолчанию
 */
date_default_timezone_set('Europe/Moscow');

/**
 * Автозагрузка классов
 */
spl_autoload_register(function ($className) {
    $filePath = str_replace("\\", "/", $className);
    $filePath = str_replace("dev_bots_ru/", "", $filePath);
    require_once $filePath . '.php';
});

try {

    // 
    if (isset($argv) && $argv) :
        for ($i = 1; $i < count($argv); $i++) :
            $parts = explode('=', $argv[$i], 2);
            if (count($parts) == 2) :
                $name = $parts[0];
                $value = $parts[1];
                $_GET[$name] = $value;
            else :
                $_GET[$parts[0]] = $parts[0];
            endif;
        endfor;
    endif;

    //
    if (isset($_GET['cron']) && isset($_GET['action'])) :
        Cron::run();
    else :
        new Router();
    endif;
}

//
catch (\Throwable $t) {

    file_put_contents(__DIR__ . '/__errors.log', "\n\n" . date('Y-m-d H:i:s') . "\n\n" . print_r($t->__toString(), 1), FILE_APPEND);
    $owner_tg_is = DB::get__bot_owner_tg_id() ?? '';
    if ($owner_tg_is) :
        TG::sendMessage($owner_tg_is, "Ошибка. Смотреть в логах.");
    endif;
}
