<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;
use RetailCrm\DeliveryModuleBundle\Model\Settings\ExtraData;
use RetailCrm\DeliveryModuleBundle\Model\Settings\PaymentType;
use RetailCrm\DeliveryModuleBundle\Model\Settings\ShipmentPoint;
use RetailCrm\DeliveryModuleBundle\Model\Settings\Status;

class Settings
{
    public const COST_CALCULATE_BY_MODULE = 'auto';
    public const COST_CALCULATE_BY_SYSTEM = 'manual';

    /**
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("defaultPayerType")
     * @Serializer\Type("string")
     */
    public $defaultPayerType;

    /**
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("costCalculateBy")
     * @Serializer\Type("string")
     */
    public $costCalculateBy;

    /**
     * @var bool
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("nullDeclaredValue")
     * @Serializer\Type("boolean")
     */
    public $nullDeclaredValue;

    /**
     * @var bool
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("lockedByDefault")
     * @Serializer\Type("boolean")
     */
    public $lockedByDefault;

    /**
     * @var array|PaymentType[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("paymentTypes")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Settings\PaymentType>")
     */
    public $paymentTypes;

    /**
     * @var array|ShipmentPoint[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("shipmentPoints")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Settings\ShipmentPoint>")
     */
    public $shipmentPoints;

    /**
     * @var array|Status[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("statuses")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Settings\Status>")
     */
    public $statuses;

    /**
     * @var array|ExtraData[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("deliveryExtraData")
     * @Serializer\Type("array")
     */
    public $deliveryExtraData;

    /**
     * @var array|ExtraData[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("shipmentExtraData")
     * @Serializer\Type("array")
     */
    public $shipmentExtraData;
}
