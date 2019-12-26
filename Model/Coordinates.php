<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Coordinates
{
    /**
     * Широта
     * @var float
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("latitude")
     * @Serializer\Type("float")
     */
    public $latitude;

    /**
     * Долгота
     * @var float
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("longitude")
     * @Serializer\Type("float")
     */
    public $longitude;
}
