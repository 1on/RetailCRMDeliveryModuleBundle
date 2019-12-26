<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use App\Entity\Account;
use Psr\Log\LoggerInterface;
use RetailCrm\ApiClient;

class RetailCrmClientFactory implements RetailCrmClientFactoryInterface
{
    public function createRetailCrmClient(Account $account, ?LoggerInterface $logger = null): ApiClient
    {
        if (null === $account) {
            throw new \LogicException('Account is not selected');
        }

        if (empty($account->getCrmUrl())) {
            throw new \LogicException('Crm url is empty');
        }

        if (empty($account->getCrmApiKey())) {
            throw new \LogicException('Crm apiKey is empty');
        }

        $retailCrmClient = new ApiClient(
            $account->getCrmUrl(),
            $account->getCrmApiKey(),
            ApiClient::V5
        );
        if ($logger) {
            $retailCrmClient->setLogger($logger);
        }

        return $retailCrmClient;
    }
}
