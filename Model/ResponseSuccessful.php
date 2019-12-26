<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseSuccessful
{
    /**
     * @var boolean
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("success")
     * @Serializer\Type("boolean")
     */
    public $success = true;

    /**
     * @var mixed
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("result")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\ResponseResult")
     */
    public $result;
}
