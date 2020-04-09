<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Settings;

use JMS\Serializer\Annotation as Serializer;

class Status
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
     * @var mixed
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("trackingStatusCode")
     * @Serializer\Type("string")
     */
    public $trackingStatusCode;
}
