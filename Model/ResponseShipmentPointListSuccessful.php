<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseShipmentPointListSuccessful extends AbstractResponseSuccessful
{
    /**
     * @var mixed
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("result")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Terminal>")
     */
    public $result;
}
