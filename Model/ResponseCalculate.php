<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseCalculate
{
    const TARIFF_COURIER = 'courier';
    const TARIFF_SELF_DELIVERY = 'selfDelivery';

    /**
     * Код тарифа
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Группа тарифов
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("group")
     * @Serializer\Type("string")
     */
    public $group;

    /**
     * Наименование тарифа
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Тип тарифа (курьерская доставка или самовывоз)
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * Описание
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     */
    public $description;

    /**
     * Стоимость доставки
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("cost")
     * @Serializer\Type("float")
     */
    public $cost;

    /**
     * Минимальный срок доставки
     * @var integer
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("minTerm")
     * @Serializer\Type("integer")
     */
    public $minTerm;

    /**
     * Максимальный срок доставки
     * @var integer
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("maxTerm")
     * @Serializer\Type("integer")
     */
    public $maxTerm;

    /**
     * Дополнительные данные доставки
     * @var array
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("extraData")
     * @Serializer\Type("array")
     */
    public $extraData;

    /**
     * Возможные дополнительные данные доставки
     * @var array
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("extraDataAvailable")
     * @Serializer\Type("array")
     */
    public $extraDataAvailable;

    /**
     * Список доступных терминалов выдачи посылки
     * @var Terminal[]
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("pickuppointList")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Terminal>")
     */
    public $pickuppointList;

    public function __construct()
    {
        $this->extraData = [];
    }
}
