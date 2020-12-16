<?php

namespace RetailCrm\DeliveryModuleBundle\Command\Traits;

trait CommandOutputFormatterTrait
{
    public function getFormattedOutput(string $message): string
    {
        return date('Y-m-d H:i:s') . ' - ' . $message;
    }
}