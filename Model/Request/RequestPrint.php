<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Request;

use JMS\Serializer\Annotation as Serializer;

class RequestPrint
{
    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * @var string[]
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("deliveryIds")
     * @Serializer\Type("array")
     */
    public $deliveryIds;
}
