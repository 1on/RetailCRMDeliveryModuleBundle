<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Intaro\CRMDeliveryBundle\Delivery\Generic\Generic;

class PackageItem
{
    /**
     * Идентификатор товара
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("offerId")
     * @Serializer\Type("string")
     */
    public $offerId;

    /**
     * Наименование товара
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Объявленная стоимость за единицу товара
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("declaredValue")
     * @Serializer\Type("float")
     */
    public $declaredValue;

    /**
     * Наложенный платеж за единицу товара
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Количество товара в упаковке
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("quantity")
     * @Serializer\Type("float")
     */
    public $quantity;

    /**
     * Свойства товара
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("properties")
     * @Serializer\Type("array<string, array>")
     */
    public $properties;
}
