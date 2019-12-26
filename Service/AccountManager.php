<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use RetailCrm\DeliveryModuleBundle\Model\Entity\Account;

class AccountManager
{
    protected $class;

    public function __construct(string $accountClass, ObjectManager $entityManager)
    {
        $this->class = $accountClass;
        $this->entityManager = $entityManager;
    }

    public function getClass(): string
    {
        return $this->getRepository()->getClassName();
    }

    public function create(): Account
    {
        $class = $this->getClass();

        return new $class();
    }

    public function find(string $id): ?Account
    {
        return $this->getRepository()->find($id);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Account
    {
        return $this->getRepository()->findOneBy($criteria, $orderBy);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository($this->class);
    }
}
