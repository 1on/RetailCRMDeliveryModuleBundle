<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Terminal
{
    /**
     * Код терминала
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Наименование терминала
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Адрес
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("address")
     * @Serializer\Type("string")
     */
    public $address;

    /**
     * Режим работы
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("schedule")
     * @Serializer\Type("string")
     */
    public $schedule;

    /**
     * Телефон
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("phone")
     * @Serializer\Type("string")
     */
    public $phone;

    /**
     * Дополнительные данные
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;

    /**
     * Координаты
     * @var Coordinates
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("coordinates")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Coordinates")
     */
    public $coordinates;
}
