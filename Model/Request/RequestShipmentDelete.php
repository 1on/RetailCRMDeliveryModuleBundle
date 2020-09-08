<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Request;

use JMS\Serializer\Annotation as Serializer;

class RequestShipmentDelete
{
    /**
     * Идентификатор отгрузки в службе доставки
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("shipmentId")
     * @Serializer\Type("string")
     */
    public $shipmentId;

    /**
     * Дополнительные данные отгрузки
     * @var array
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;
}
