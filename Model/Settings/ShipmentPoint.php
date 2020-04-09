<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Settings;

use JMS\Serializer\Annotation as Serializer;

class ShipmentPoint
{
    /**
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * @var int
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("shipmentPointId")
     * @Serializer\Type("integer")
     */
    public $shipmentPointId;

    /**
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("shipmentPointLabel")
     * @Serializer\Type("string")
     */
    public $shipmentPointLabel;
}
