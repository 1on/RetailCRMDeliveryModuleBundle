<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class StoreWorkTimeItem
{
    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("startTime")
     * @Serializer\Type("string")
     */
    public $startTime;

    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("endTime")
     * @Serializer\Type("string")
     */
    public $endTime;

    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("lunchStartTime")
     * @Serializer\Type("string")
     */
    public $lunchStartTime;

    /**
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("lunchEndTime")
     * @Serializer\Type("string")
     */
    public $lunchEndTime;
}