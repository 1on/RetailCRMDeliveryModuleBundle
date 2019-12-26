<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use RetailCrm\DeliveryModuleBundle\Entity\DeliveryOrder;

class DeliveryOrderManager
{
    protected $class;

    public function __construct(string $deliveryOrderClass, ObjectManager $entityManager)
    {
        $this->class = $deliveryOrderClass;
        $this->entityManager = $entityManager;
    }

    public function getClass(): string
    {
        return $this->getRepository()->getClassName();
    }

    public function create(): DeliveryOrder
    {
        $class = $this->getClass();

        return new $class();
    }

    public function findBy(array $criteria): array
    {
        return $this->getRepository()->findBy($criteria);
    }

    public function findOneBy(array $criteria): ?DeliveryOrder
    {
        return $this->getRepository()->findOneBy($criteria);
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
