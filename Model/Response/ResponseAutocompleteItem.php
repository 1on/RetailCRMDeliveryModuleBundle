<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Response;

use JMS\Serializer\Annotation as Serializer;

class ResponseAutocompleteItem
{
    /**
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("value")
     * @Serializer\Type("string")
     */
    public $value;

    /**
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("label")
     * @Serializer\Type("string")
     */
    public $label;

    /**
     * @var string
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     */
    public $description;

    public function __construct($value, $label, $description = null)
    {
        $this->value = $value;
        $this->label = $label;
        $this->description = $description;
    }
}
