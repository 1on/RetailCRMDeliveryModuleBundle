<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class PackageItem
{
    /**
     * Идентификатор товара.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("offerId")
     * @Serializer\Type("string")
     */
    public $offerId;

    /**
     * Наименование товара.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Объявленная стоимость за единицу товара.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("declaredValue")
     * @Serializer\Type("float")
     */
    public $declaredValue;

    /**
     * Наложенный платеж за единицу товара.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cod")
     * @Serializer\Type("float")
     */
    public $cod;

    /**
     * Ставка НДС
     *
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("vatRate")
     * @Serializer\Type("float")
     */
    public $vatRate;

    /**
     * Количество товара в упаковке.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("quantity")
     * @Serializer\Type("float")
     */
    public $quantity;

    /**
     * Единица измерения товара.
     *
     * @var Unit
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("unit")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Unit")
     */
    public $unit;

    /**
     * Стоимость товара.
     *
     * @var float
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("cost")
     * @Serializer\Type("float")
     */
    public $cost;

    /**
     * Свойства товара.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("properties")
     * @Serializer\Type("array<string, array>")
     */
    public $properties;
}
