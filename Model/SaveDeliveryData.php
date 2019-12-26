<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class SaveDeliveryData
{
    use Traits\ExtraDataTrait;

    /**
     * Адрес отгрузки
     * @var DeliveryAddress
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("shipmentAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $shipmentAddress;

    /**
     * Адрес доставки
     * @var DeliveryAddress
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $deliveryAddress;

    /**
     * Доставка наложенным платежом
     * @var boolean
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("withCod")
     * @Serializer\Type("boolean")
     */
    public $withCod;

    /**
     * Величина наложенного платежа за услуги доставки
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Стоимость доставки (указывается в накладной в случае предоплаты)
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cost")
     * @Serializer\Type("float")
     */
    public $cost;

    /**
     * Код тарифа
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("tariff")
     * @Serializer\Type("string")
     */
    public $tariff;

    /**
     * Плательщик за услуги доставки
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("string")
     */
    public $payerType;

    /**
     * Дата отгрузки
     * @var DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("shipmentDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $shipmentDate;

    /**
     * Дата доставки
     * @var DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $deliveryDate;

    /**
     * Время доставки ("custom" не ипользуется)
     * @var Intaro\CRMBundle\Entity\Model\DeliveryTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $deliveryTime;

    /**
     * Дополнительные данные доставки
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
