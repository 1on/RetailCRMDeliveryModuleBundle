<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class SaveDeliveryData
{
    /**
     * Адрес отгрузки.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("shipmentAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $shipmentAddress;

    /**
     * Адрес доставки.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryAddress")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $deliveryAddress;

    /**
     * Тип оплаты для наложенного платежа.
     *
     * @var PaymentType
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("codPaymentType")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\PaymentType")
     */
    public $codPaymentType;

    /**
     * Доставка наложенным платежом
     *
     * @var bool
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("withCod")
     * @Serializer\Type("boolean")
     */
    public $withCod;

    /**
     * Величина наложенного платежа за услуги доставки.
     *
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Стоимость доставки (указывается в накладной в случае предоплаты).
     *
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cost")
     * @Serializer\Type("float")
     */
    public $cost;

    /**
     * Ставка НДС для услуги доставки.
     *
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("vatRate")
     * @Serializer\Type("float")
     */
    public $vatRate;

    /**
     * Код тарифа.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("tariff")
     * @Serializer\Type("string")
     */
    public $tariff;

    /**
     * Плательщик за услуги доставки.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("string")
     */
    public $payerType;

    /**
     * Дата отгрузки.
     *
     * @var \DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("shipmentDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $shipmentDate;

    /**
     * Дата доставки.
     *
     * @var \DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryDate")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $deliveryDate;

    /**
     * Время доставки ("custom" не ипользуется).
     *
     * @var \RetailCrm\DeliveryModuleBundle\Model\DeliveryTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $deliveryTime;

    /**
     * Дополнительные данные доставки.
     *
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
