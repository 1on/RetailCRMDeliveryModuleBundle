<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseCalculateSuccessful extends AbstractResponseSuccessful
{
    /**
     * @var mixed
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("result")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\ResponseCalculate>")
     */
    public $result;
}
