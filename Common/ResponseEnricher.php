<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\Common\ResponseEnricher\ResponseEnricherBase;
use dev_bots_ru\Common\ResponseEnricher\ResponseEnricherCloud;
use dev_bots_ru\Common\ResponseEnricher\ResponseEnricherHosting;

/**
 * Обогощатель входящих и исходящих сообщений, можно назвать и хелпером
 */
class ResponseEnricher
{
    /**
     * Обогатитель ответов бота
     *
     * @return ResponseEnricherBase
     */
    public static function _base(): ResponseEnricherBase
    {
        return ResponseEnricherBase::__get_instance();
    }

    /**
     * Обогатитель ответов хостингового типа аккаунта
     *
     * @return ResponseEnricherHosting
     */
    public static function _hosting(): ResponseEnricherHosting
    {
        return ResponseEnricherHosting::__get_instance();
    }

    /**
     * Обогатитель ответов облачного типа аккаунта
     *
     * @return ResponseEnricherCloud
     */
    public static function _cloud(): ResponseEnricherCloud
    {
        return ResponseEnricherCloud::__get_instance();
    }
}
