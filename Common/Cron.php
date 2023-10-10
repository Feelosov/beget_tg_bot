<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\General\Config;
use dev_bots_ru\General\DB;
use dev_bots_ru\Senders\Beget_hosting;
use dev_bots_ru\Senders\TG;

class Cron
{
    public static function run()
    {
        $cron_action = $_GET['action'];
        if (method_exists(__CLASS__, $cron_action)) :
            self::$cron_action();
        endif;
    }

    /**
     * Обработка запроса на создание изображения средней нагрузки хостинга
     *
     * @return void
     */
    public static function avg_load_by_day_chart()
    {
        $account = $_GET['account'] ?? '';
        $avg_load_by_site = Beget_hosting::statGetSiteListLoad($account);

        $days = $_GET['days'] ?? 7;
        if ($days < 7) :
            $days = 7;
        elseif ($days > 31) :
            $days = 31;
        endif;

        // TODO: сделать уведомление о слишком большом кол-ве сайтов
        if (count($avg_load_by_site) > 20) :
            exit;
        endif;

        if (
            $avg_load_by_site['answer']['status'] == 'success'
            && isset($avg_load_by_site['answer']['result'])
            && $avg_load_by_site['answer']['result']
        ) :

            // Получим суммарную нагрузку, заодно и ID сайтов
            $cp_total = 0.0;
            foreach ($avg_load_by_site['answer']['result'] as $load_data) :
                $cp_total += $load_data['cp'];
                $site_ids[] = $load_data['id'];
            endforeach;

            $cp_site = [];
            // Получим по каждому сайту данные о нагрузке и просуммируем
            foreach ($site_ids as $num => $site_id) :
                $site_load_by_days = Beget_hosting::statGetSiteLoad($account, $site_id);
                if (
                    $site_load_by_days['answer']['status'] == 'success'
                    && isset($site_load_by_days['answer']['result'])
                    && $site_load_by_days['answer']['result']
                ) :
                    $site_load_by_days = array_reverse($site_load_by_days['answer']['result']['days']);
                    $by_days = array_slice($site_load_by_days, 0, $days, true);
                    foreach ($by_days as $num => $day_load) :
                        // $date = date('Y-m-d', strtotime($day_load['date']));
                        $date = date('D', strtotime($day_load['date'])) . " " . date('d.m', strtotime($day_load['date']));
                        $cp_site['x'][$num] = $date;
                        $cp_site['y'][$num] += $day_load['value'];
                    endforeach;

                endif;
            endforeach;
            $cp_site['x'] = array_reverse(array_values(array_slice($cp_site['x'], 0, $days)));
            $cp_site['y'] = array_reverse(array_values(array_slice($cp_site['y'], 0, $days)));

            $chart_name = "Average load (avg month: " . round($cp_total, 2) . "), account " . $account;
            $chart_image_filename = Charts::draw_v($account, $chart_name, $cp_site['x'], $cp_site['y']);
            $chart_url = DB::get__bot_charts_url($account) . '/' . $chart_image_filename;
            $chart_path = Config::get__db_dir__bot_charts_dir($account) . '/' . $chart_image_filename;

            $tg_user_id = DB::get__bot_owner_tg_id();
            TG::sendPhoto($tg_user_id, $chart_url);

            unlink($chart_path);

        endif;
    }

    /**
     * Размер папок в корне виртуального хостинга
     *
     * @return void
     */
    public static function disk_usage()
    {
        $account = $_GET['account'];
        preg_match("#\/home\/[a-z]\/([a-z_\d]{5,20})\/#", __DIR__, $matches);
        $account = $matches[1] ?? '';

        if (!$account) :
            return;
        endif;

        $account_letter = substr($account, 0, 1);
        $path = "/home/" . $account_letter . '/' . $account;

        $subfolders = scandir($path);
        unset($subfolders[array_search('.', $subfolders)]);
        unset($subfolders[array_search('..', $subfolders)]);

        if ($subfolders) :
            $total_size = 0;
            foreach ($subfolders as $num => $folder) :
                $folder_path = $path  . '/' . $folder;
                if (is_dir($folder_path)) :
                    $escaped_path = escapeshellarg($folder_path);
                    $command = "du -sh " . $escaped_path;
                    $output = [];
                    exec($command, $output, $code);
                    if ($code === 0) :
                        $result = implode("\n", $output);
                        if (preg_match('/([\d,\.]+)([GMK]?)\s/', $result, $matches)) :
                            $size = (float)str_replace(',', '.', $matches[1]);
                            $unit = $matches[2];
                            switch ($unit):
                                case 'G':
                                    $size *= 1024 * 1024 * 1024;
                                    break;
                                case 'M':
                                    $size *= 1024 * 1024;
                                    break;
                                case 'K':
                                    $size *= 1024;
                                    break;
                                default:
                                    break;
                            endswitch;
                            $total_size += $size / 1024 / 1024;
                            $du['x'][$num] = $size / 1024 / 1024;
                            $du['y'][$num] = $folder;
                        endif;
                    endif;
                endif;
            endforeach;
        endif;

        $du['x'] = array_values($du['x']);
        $du['y'] = array_values($du['y']);
        array_multisort($du['x'], $du['y']);

        if ($du) :
            $chart_image_filename = Charts::draw_h(
                $account,
                "Disk usage (used: " . number_format($total_size, 0, ".", " ") . " Mb), account " . $account . "",
                $du['x'],
                $du['y']
            );

            $chart_url = DB::get__bot_charts_url($account) . '/' . $chart_image_filename;
            $chart_path = Config::get__db_dir__bot_charts_dir($account) . '/' . $chart_image_filename;

            $tg_user_id = DB::get__bot_owner_tg_id();
            TG::sendPhoto($tg_user_id, $chart_url);

            unlink($chart_path);
        endif;
    }
}
