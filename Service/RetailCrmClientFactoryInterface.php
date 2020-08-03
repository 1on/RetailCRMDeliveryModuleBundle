<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use Psr\Log\LoggerInterface;
use RetailCrm\ApiClient;
use RetailCrm\DeliveryModuleBundle\Model\Entity\Account;

interface RetailCrmClientFactoryInterface
{
    public function createRetailCrmClient(Account $account, ?LoggerInterface $logger = null): ApiClient;
}
