<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Plate
{
    /**
     * Код печатной формы
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Наименование печатной формы
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("label")
     * @Serializer\Type("string")
     */
    public $label;

    public function __construct($code, $label)
    {
        $this->code = $code;
        $this->label = $label;
    }
}
