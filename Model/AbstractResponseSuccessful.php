<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractResponseSuccessful
{
    /**
     * @var bool
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("success")
     * @Serializer\Type("boolean")
     */
    public $success = true;
}
