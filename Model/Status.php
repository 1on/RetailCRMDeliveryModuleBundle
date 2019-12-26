<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Status
{
    /**
     * Код статуса доставки
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Наименование статуса
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * Если заказ находится в статусе у которого isEditable:true, это означает можно редактировать данные доставки
     * @var bool
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("isEditable")
     * @Serializer\Type("boolean")
     */
    public $isEditable = false;

    public function __construct($code, $name, $isEditable)
    {
        $this->code = $code;
        $this->name = $name;
        $this->isEditable = $isEditable;
    }
}
