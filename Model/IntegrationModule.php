<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class IntegrationModule
{
    /**
     * Код экземпляра модуля
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Общий символьный код модуля
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("integrationCode")
     * @Serializer\Type("string")
     */
    public $integrationCode;

    /**
     * Ключ активности модуля
     * @var boolean
     *
     * @Serializer\Groups({"set", "get", "activity"})
     * @Serializer\SerializedName("active")
     * @Serializer\Type("boolean")
     */
    public $active;

    /**
     * Работа модуля заморожена
     * @var boolean
     *
     * @Serializer\Groups({"activity"})
     * @Serializer\SerializedName("freeze")
     * @Serializer\Type("boolean")
     */
    public $freeze;

    /**
     * Наименование модуля
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Ссылка на svg логотип модуля
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("logo")
     * @Serializer\Type("string")
     */
    public $logo;

    /**
     * ID подключения
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("clientId")
     * @Serializer\Type("string")
     */
    public $clientId;

    /**
     * Базовый url, на который делает запросы RetailCRM
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("baseUrl")
     * @Serializer\Type("string")
     */
    public $baseUrl;

    /**
     * Относительные пути от базового URL до конкретных методов
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("actions")
     * @Serializer\Type("array<string, string>")
     */
    public $actions;

    /**
     * Список стран для которых доступен модуль
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("availableCountries")
     * @Serializer\Type("array")
     */
    public $availableCountries;

    /**
     * URL настроек модуля
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("accountUrl")
     * @Serializer\Type("string")
     */
    public $accountUrl;

    /**
     * Массив конфигураций интеграций
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("integrations")
     * @Serializer\Type("array")
     */
    public $integrations;
}
