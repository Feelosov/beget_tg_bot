<?php

namespace dev_bots_ru\Common\ResponseEnricher;

use dev_bots_ru\Common\Patterns\Singleton;

/**
 * Насыщатель ответов от хостингового типа аккаунта
 */
class ResponseEnricherHosting extends Singleton
{
    /**
     * Обработчик ответов из области действия Аккаунт
     *
     * @return ResponseEnricherHostingAccount
     */
    public function account(): ResponseEnricherHostingAccount
    {
        return ResponseEnricherHostingAccount::__get_instance();
    }

    /**
     * Обработчик ответов из области действия Бэкапы
     *
     * @return ResponseEnricherHostingBackups
     */
    public function backups(): ResponseEnricherHostingBackups
    {
        return ResponseEnricherHostingBackups::__get_instance();
    }

    /**
     * Обработчик ответов из области действия Cron
     *
     * @return ResponseEnricherHostingCron
     */
    public function cron(): ResponseEnricherHostingCron
    {
        return ResponseEnricherHostingCron::__get_instance();
    }

    /**
     * Обработчик ответов из области действия FTP
     *
     * @return ResponseEnricherHostingFTP
     */
    public function ftp(): ResponseEnricherHostingFTP
    {
        return ResponseEnricherHostingFTP::__get_instance();
    }
}
