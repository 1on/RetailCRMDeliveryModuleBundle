<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Unit
{
    /**
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("sym")
     * @Serializer\Type("string")
     */
    public $sym;
}
