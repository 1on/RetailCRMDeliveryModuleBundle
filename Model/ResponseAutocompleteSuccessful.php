<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class ResponseAutocompleteSuccessful
{
    /**
     * @var boolean
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("success")
     * @Serializer\Type("boolean")
     */
    public $success = true;

    /**
     * @var mixed
     *
     * @Serializer\Groups({"get", "response"})
     * @Serializer\SerializedName("result")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\ResponseAutocompleteItem>")
     */
    public $result;
}
