<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Store
{
    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var StoreWorkTime
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("workTime")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\StoreWorkTime")
     */
    public $workTime;
}
