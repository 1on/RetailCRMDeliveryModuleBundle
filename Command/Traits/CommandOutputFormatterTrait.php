<?php

namespace RetailCrm\DeliveryModuleBundle\Command\Traits;

trait CommandOutputFormatterTrait
{
    public function output(string $level, string $message): string
    {
        return sprintf("%s %s %s", date('Y-m-d H:i:s'), strtoupper($level), $message);
    }

    public function infoOutput(string $message): string
    {
        return $this->output('INFO', $message);
    }
}