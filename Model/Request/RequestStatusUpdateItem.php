<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Request;

use JMS\Serializer\Annotation as Serializer;
use RetailCrm\DeliveryModuleBundle\Model\StatusInfo;

class RequestStatusUpdateItem
{
    /**
     * Идентификатор доставки с СД
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("deliveryId")
     * @Serializer\Type("string")
     */
    public $deliveryId;

    /**
     * Трек номер
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("trackNumber")
     * @Serializer\Type("string")
     */
    public $trackNumber;

    /**
     * История смены статусов доставки
     * @var StatusInfo[]
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("history")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\StatusInfo>")
     */
    public $history;

    /**
     * Массив дополнительных данных доставки
     * @var array
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;

    public function __construct()
    {
        $this->history = [];
    }
}
