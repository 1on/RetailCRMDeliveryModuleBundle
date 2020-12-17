<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class StoreWorkTime
{
    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("mo")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $monday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("tu")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $tuesday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("we")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $wednesday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("th")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $thursday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("fr")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $friday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("sa")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $saturday;

    /**
     * @var array<StoreWorkTimeItem>
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("su")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StoreWorkTimeItem>")
     */
    public $sunday;
}