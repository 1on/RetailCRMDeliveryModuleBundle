<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class ResponseLoadDeliveryData
{
    /**
     * Трек номер
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("trackNumber")
     * @Serializer\Type("string")
     */
    public $trackNumber;

    /**
     * Стоимость доставки
     * @var float
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("cost")
     * @Serializer\Type("float")
     */
    public $cost;

    /**
     * Дата отгрузки
     * @var \DateTime
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("shipmentDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $shipmentDate;

    /**
     * Дата доставки
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("deliveryDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $deliveryDate;

    /**
     * Время доставки
     * @var DeliveryTime
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("deliveryTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $deliveryTime;

    /**
     * Код тарифа
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("tariff")
     * @Serializer\Type("string")
     */
    public $tariff;

    /**
     * Наименование тарифа
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("tariffName")
     * @Serializer\Type("string")
     */
    public $tariffName;

    /**
     * Плательщик за доставку
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("string")
     */
    public $payerType;

    /**
     * Текущий статус достаквки
     * @var StatusInfo
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("status")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\StatusInfo")
     */
    public $status;

    /**
     * Дополнительные данные доставки
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;

    /**
     * Адрес отгрузки
     * @var DeliveryAddress
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("shipmentAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $shipmentAddress;

    /**
     * Адрес доставки
     * @var DeliveryAddress
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("deliveryAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $deliveryAddress;

    public $additionalData;

    public function __construct()
    {
        $this->extraData = [];
        $this->additionalData = [];
    }
}
