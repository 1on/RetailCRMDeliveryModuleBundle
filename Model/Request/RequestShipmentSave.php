<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Request;

use JMS\Serializer\Annotation as Serializer;
use RetailCrm\DeliveryModuleBundle\Model\Traits\ExtraDataTrait;

class RequestShipmentSave
{
    use ExtraDataTrait;

    /**
     * Идентификатор отгрузки в службе доставки. Передается если требуется отредактировать уже оформленную отгрузку
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("shipmentId")
     * @Serializer\Type("string")
     */
    public $shipmentId;

    /**
     * Менеджер, отвечающий за отгрузку
     * @var Manager
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("manager")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Manager")
     */
    public $manager;

    /**
     * Дата отгрузки
     * @var DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("date")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $date;

    /**
     * Время доставки ("custom" не ипользуется)
     * @var RetailCrm\DeliveryModuleBundle\Model\DeliveryTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("time")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryTime")
     */
    public $time;

    /**
     * Адрес отгрузки
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("address")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\DeliveryAddress")
     */
    public $address;

    /**
     * Массив идентификаторов оформленных доставок в службе доставки
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("orders")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\ShipmentOrder>")
     */
    public $orders;

    /**
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("comment")
     * @Serializer\Type("string")
     */
    public $comment;

    /**
     * Дополнительные данные отгрузки
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
