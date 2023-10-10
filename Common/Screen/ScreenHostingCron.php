<?php

namespace dev_bots_ru\Common\Screen;

use dev_bots_ru\Common\Button;
use dev_bots_ru\Common\Patterns\Singleton;
use dev_bots_ru\Common\Screen\ScreenTrait;

class ScreenHostingCron extends Singleton
{
    use ScreenTrait;

    /**
     * Список действий категории Cron
     *
     * @param string $account_name
     * @return array|string
     */
    public function manage_cron(string $account_name, bool $return_name = false): array|string
    {
        if (!$account_name && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран управления Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран отображения справки по Cron
     *
     * @param string    $account_name
     * @param string    $additional_text
     * @param boolean   $return_name
     * @return array|string
     */
    public function get_help(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран справки по Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран отображения списка всех задач CronTab
     *
     * @param string    $account_name
     * @param string    $additional_text
     * @param boolean   $return_name
     * @return array|string
     */
    public function get_list(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран списка задач Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран для добавления нового задания
     *
     * @param string    $account
     * @param string    $additional_text
     * @param boolean   $return_name
     * @return array|string
     */
    public function add(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран добавления задачи Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= "Чтобы добавить задачу впишите расписание, далее через пробел команду.";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>";
        $text .= "\n";
        $text .= "<code>* * * * * wget -O /dev/null -t 1 -q https://site.ru/cron.php</code>";
        $text .= "\n\n";
        $text .= "<u>Заготовки расписаний:</u>";
        $text .= "\n\n";
        $text .= "🔸 Ежеминутно:\n <code>* * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежечасно:\n <code>0 * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежедневно в 00:00:\n <code>0 0 * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Еженедельно в ПН в 00:00:\n <code>0 0 * * 1</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежедневно в 3:30 утра:\n <code>30 3 * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Еженедельно в ВС в 8:00 утра:\n <code>0 8 * * 0</code>";
        $text .= "\n\n";
        $text .= "🔸 В будни в 9:00 утра:\n <code>0 9 * * 1-5</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежечасно в 15 минут:\n <code>15 * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежемесячно 15-го в 6:30 утра:\n <code>30 6 15 * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежегодно 1 янв. в 00:00:\n <code>0 0 1 1 *</code>";
        $text .= "\n\n";
        $text .= "<u>Заготовки команд:</u>";
        $text .= "\n\n";
        $text .= "🔸 Обратиться по адресу:\n<code>wget -O /dev/null -t 1 -q https://</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск PHP-скрипта версии 8.2:\n<code>/usr/local/bin/php8.2 ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск PHP-скрипта версии 7.4:\n<code>/usr/local/bin/php7.4 ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск Perl-скрипта:\n<code>/usr/bin/perl ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск Bash-скрипта:\n<code>/bin/bash ~/</code>";
        $text .= "\n\n";

        $path = preg_replace("#\/home\/[a-z]\/[a-z_\d]{5,20}\/#iu", '', __DIR__);
        $path = str_replace('/Common/Screen', '', $path);
        $path = $path . '/index.php';

        $text .= "❇️ Пример статистики по CP каждый час:\n";
        $text .= "<code>1 * * * * /usr/local/bin/php8.2 ~/" . $path . " cron=true action=avg_load_by_day_chart days=14 account=" . $account_name . "</code>";

        if (strpos(__DIR__, $account_name, 0) !== false) :
            $text .= "\n\n";
            $text .= "❇️ Пример статистики по диску каждый час (только для этого аккаунта):\n";
            $text .= "<code>1 * * * * /usr/local/bin/php8.2 ~/" . $path . " cron=true action=disk_usage account=" . $account_name . "</code>";
        endif;

        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран для изменения задания
     *
     * @param string    $account
     * @param string    $additional_text
     * @param boolean   $return_name
     * @return array|string
     */
    public function edit(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран редактирования задачи Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= "Чтобы изменить задачу впишите новый статус (0 - выкл., 1 - вкл.), далее пробел и ID задания, ";
        $text .= "далее пробел и расписание, далее пробел и команду.";
        $text .= "\n\n";
        $text .= "<b>Пример:</b>";
        $text .= "\n";
        $text .= "<code>0 2084524 * * * * * wget -O /dev/null -t 1 -q https://site.ru/cron.php</code>";
        $text .= "\n\n";
        $text .= "<u>Заготовки расписаний:</u>";
        $text .= "\n\n";
        $text .= "🔸 Ежеминутно:\n <code>* * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежечасно:\n <code>0 * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежедневно в 00:00:\n <code>0 0 * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Еженедельно в ПН в 00:00:\n <code>0 0 * * 1</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежедневно в 3:30 утра:\n <code>30 3 * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Еженедельно в ВС в 8:00 утра:\n <code>0 8 * * 0</code>";
        $text .= "\n\n";
        $text .= "🔸 В будни в 9:00 утра:\n <code>0 9 * * 1-5</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежечасно в 15 минут:\n <code>15 * * * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежемесячно 15-го в 6:30 утра:\n <code>30 6 15 * *</code>";
        $text .= "\n\n";
        $text .= "🔸 Ежегодно 1 янв. в 00:00:\n <code>0 0 1 1 *</code>";
        $text .= "\n\n";
        $text .= "<u>Заготовки команд:</u>";
        $text .= "\n\n";
        $text .= "🔸 Обратиться по адресу:\n<code>wget -O /dev/null -t 1 -q https://</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск PHP-скрипта версии 8.2:\n<code>/usr/local/bin/php8.2 ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск PHP-скрипта версии 7.4:\n<code>/usr/local/bin/php7.4 ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск Perl-скрипта:\n<code>/usr/bin/perl ~/</code>";
        $text .= "\n\n";
        $text .= "🔸 Запуск Bash-скрипта:\n<code>/bin/bash ~/</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран для удаления задачи
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function delete(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран удаления задачи Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= "Чтобы удалить задачу отправьте ее ID. Также можно указать несколько ID через пробел и все они будут удалены.";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран для изменения статуса задачи
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function change_status(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран изменения статуса задачи Cron.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= "Чтобы изменить статус задачи отправьте статус (0 - выкл., 1 - вкл), пробел и ее ID.";
        $text .= "\n\n";
        $text .= "Пример:";
        $text .= "\n";
        $text .= "<code>0 2084524</code>";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран запроса email для отправки результатов работы Cron.
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function get_email(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран получения отчетного email.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }

    /**
     * Экран установки email для отправки результатов работы Cron.
     *
     * @param  string       $account_name
     * @param  string       $additional_text
     * @param  boolean      $return_name
     * @return array|string
     */
    public function set_email(string $account_name, string $additional_text, bool $return_name = false): array|string
    {
        if (!$account_name && !$additional_text && $return_name) :
            return __FUNCTION__;
        endif;

        $text = "<b>Экран установки отчетного email.</b>";
        $text .= "\n\n";
        $text .= "Текущий аккаунт: <code>" . $account_name . "</code>";
        $text .= "\n\n\n";
        $text .= "Впишите адрес электропочты, на которую желаете получать <u>ошибки</u> о выполнении Cron-запросов. ";
        $text .= "Можете отправить <code>0</code>, чтобы отключить отправку уведомлений.";
        $text .= "\n\n";
        $text .= $additional_text;

        $buttons = Button::_hosting()->cron()->case__cron_actions($account_name);

        return ScreenTrait::__create_screen($text, $buttons);
    }
}
