<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class RequestCalculate
{
    use Traits\ExtraDataTrait;

    /**
     * Адрес отгрузки.
     *
     * @var DeliveryAddress
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("shipmentAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $shipmentAddress;

    /**
     * Адрес доставки.
     *
     * @var string
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $deliveryAddress;

    /**
     * Набор упаковок.
     *
     * @var Package[]
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("packages")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Package>")
     */
    public $packages;

    /**
     * Объявленная стоимость.
     *
     * @var float
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("declaredValue")
     * @Serializer\Type("float")
     */
    public $declaredValue;

    /**
     * Наложенный платеж.
     *
     * @var float
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Плательщик за доставку.
     *
     * @var string
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("string")
     */
    public $payerType;

    /**
     * Дата доставки.
     *
     * @var \DateTime
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $deliveryDate;

    /**
     * Время доставки.
     *
     * @var DeliveryTime
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $deliveryTime;

    /**
     * Валюта.
     *
     * @var string
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("currency")
     * @Serializer\Type("string")
     */
    public $currency;

    /**
     * Дополнительные данные доставки.
     *
     * @var array
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
