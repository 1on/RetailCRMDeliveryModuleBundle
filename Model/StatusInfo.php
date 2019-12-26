<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class StatusInfo
{
    /**
     * Код статуса доставки
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Дата обновления статуса доставки
     * @var \DateTime
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("updatedAt")
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP'>")
     */
    public $updatedAt;

    /**
     * Комментарий к статусу
     * @var string
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("comment")
     * @Serializer\Type("string")
     */
    public $comment;
}
