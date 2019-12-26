<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseSuccessful extends AbstractResponseSuccessful
{
    /**
     * @var mixed
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("result")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\ResponseResult")
     */
    public $result;
}
