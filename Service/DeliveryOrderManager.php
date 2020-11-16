<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use RetailCrm\DeliveryModuleBundle\Model\Entity\DeliveryOrder;

class DeliveryOrderManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    public function __construct(string $deliveryOrderClass, ObjectManager $entityManager)
    {
        $this->class = $deliveryOrderClass;
        $this->entityManager = $entityManager;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function create(): DeliveryOrder
    {
        $class = $this->getClass();

        return new $class();
    }

    public function find(int $id): ?DeliveryOrder
    {
        return $this->getRepository()->find($id);
    }

    public function findBy(array $criteria): array
    {
        return $this->getRepository()->findBy($criteria);
    }

    public function findOneBy(array $criteria): ?DeliveryOrder
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
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
