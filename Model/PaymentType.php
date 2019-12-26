<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class PaymentType
{
    /**
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public string $code;

    /**
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public string $name;
}
