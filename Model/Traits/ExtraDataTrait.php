<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Traits;

trait ExtraDataTrait
{
    public function getExtraData($fieldCode)
    {
        if (!isset($this->extraData[$fieldCode])) {
            return null;
        } else {
            return $this->extraData[$fieldCode];
        }
    }
}
