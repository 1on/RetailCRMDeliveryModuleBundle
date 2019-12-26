<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ShipmentOrder
{
    /**
     * Идентификатор оформленной доставки в службе доставки.
     *
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("deliveryId")
     * @Serializer\Type("string")
     */
    public $deliveryId;

    /**
     * Набор упаковок.
     *
     * @var Package[]
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("packages")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Package>")
     */
    public $packages;
}
