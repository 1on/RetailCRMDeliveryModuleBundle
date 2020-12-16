<?php

namespace RetailCrm\DeliveryModuleBundle\Command\Traits;

trait CommandOutputFormatterTrait
{
    public function getMessage(string $level, string $message): string
    {
        return sprintf("%s %s %s", date('Y-m-d H:i:s'), strtoupper($level), $message);
    }

    public function getInfoMessage(string $message): string
    {
        return $this->getMessage('INFO', $message);
    }

    public function getErrorMessage(string $message): string
    {
        return $this->getMessage('ERROR', $message);
    }
}