<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ResponseShipmentSave
{
    /**
     * Идентификатор отгрузки в службе доставки.
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("shipmentId")
     * @Serializer\Type("string")
     */
    public $shipmentId;

    /**
     * Дополнительные данные доставки
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array<string, string>")
     */
    public $extraData;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata
            ->addPropertyConstraint('shipmentId', new Assert\NotBlank());
    }
}
