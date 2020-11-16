<?php

namespace RetailCrm\DeliveryModuleBundle\Command\Traits;

use Doctrine\ORM\Tools\Pagination\Paginator;

trait AccountAwareTrait
{
    protected function getAccounts(int $accountId = null): \ArrayIterator
    {
        if (null === $accountId) {
            return new \ArrayIterator([$this->accountManager->find($accountId)]);
        }

        $accountQuery = $this->accountManager->getRepository()->createQueryBuilder('account')
            ->where('account.active = true')
            ->andWhere('account.freeze != true')
            ->addOrderBy('account.id')
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(100)
        ;
        return (new Paginator($accountQuery))->getIterator();
    }
}