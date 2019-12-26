<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use App\Entity\Account;
use Psr\Log\LoggerInterface;
use RetailCrm\ApiClient;

interface RetailCrmClientFactoryInterface
{
    public function createRetailCrmClient(Account $account, ?LoggerInterface $logger = null): ApiClient;
}
