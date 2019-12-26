<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class RequestShipmentPointList
{
    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("country")
     * @Serializer\Type("string")
     */
    public $country;

    /**
     * @var string
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("region")
     * @Serializer\Type("string")
     */
    public $region;

    /**
     * @var int
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("regionId")
     * @Serializer\Type("integer")
     */
    public $regionId;

    /**
     * @var string
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("city")
     * @Serializer\Type("string")
     */
    public $city;

    /**
     * @var int
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("cityId")
     * @Serializer\Type("integer")
     */
    public $cityId;
}
