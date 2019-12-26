<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Intaro\CRMApiBundle\Validator\Constraints\DocSubEntity;
use Intaro\CRMBundle\Entity\Model\DeliveryTime;
use Intaro\CRMDeliveryBundle\Delivery\Generic\Generic;
use RetailCrm\DeliveryModuleBundle\Model\Package;

class RequestCalculate
{
    use Traits\ExtraDataTrait;

    /**
     * Адрес отгрузки
     * @var RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("shipmentAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $shipmentAddress;

    /**
     * Адрес доставки
     * @var string
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $deliveryAddress;

    /**
     * Набор упаковок
     * @var Intaro\CRMDeliveryBundle\Form\Model\Package
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("packages")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Package>")
     */
    public $packages;

    /**
     * Объявленная стоимость
     * @var float
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("declaredValue")
     * @Serializer\Type("float")
     */
    public $declaredValue;

    /**
     * Наложенный платеж
     * @var float
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Плательщик за доставку
     * @var string
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("string")
     */
    public $payerType;

    /**
     * Дата доставки
     * @var \DateTime $deliveryDate
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $deliveryDate;

    /**
     * Время доставки
     * @var DeliveryTime $deliveryTime
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("deliveryTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $deliveryTime;

    /**
     * Валюта
     * @var string $currency
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("currency")
     * @Serializer\Type("string")
     */
    public $currency;

    /**
     * Дополнительные данные доставки
     * @var array
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
