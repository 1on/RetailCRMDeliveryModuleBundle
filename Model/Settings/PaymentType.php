<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Settings;

use JMS\Serializer\Annotation as Serializer;

class PaymentType
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
     * @var bool
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("active")
     * @Serializer\Type("boolean")
     */
    public $active = true;

    /**
     * @var bool
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("boolean")
     */
    public $cod = false;
}
