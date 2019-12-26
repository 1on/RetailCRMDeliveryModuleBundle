<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class RequestDelete
{
    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("deliveryId")
     * @Serializer\Type("string")
     */
    public $deliveryId;
}
