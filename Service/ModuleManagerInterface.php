<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use RetailCrm\ApiClient;
use RetailCrm\DeliveryModuleBundle\Model\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Model\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Model\RequestCalculate;
use RetailCrm\DeliveryModuleBundle\Model\RequestDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestPrint;
use RetailCrm\DeliveryModuleBundle\Model\RequestSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentPointList;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseLoadDeliveryData;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\Terminal;

interface ModuleManagerInterface
{
    const STATUS_UPDATE_LIMIT = 100;

    public function getAccountCode(): string;

    public function getAccount(): ?Account;

    public function setAccount(Account $account): self;

    public function updateModuleConfiguration(): bool;

    public function calculateDelivery(RequestCalculate $data): array;

    public function saveDelivery(RequestSave $data, DeliveryOrder $delivery = null): ResponseSave;

    public function getDelivery(string $externalId): ResponseLoadDeliveryData;

    public function deleteDelivery(RequestDelete $request, DeliveryOrder $delivery): bool;

    /**
     * @return Terminal[]
     */
    public function getShipmentPointList(RequestShipmentPointList $request): array;

    public function saveShipment(RequestShipmentSave $data): ResponseShipmentSave;

    public function deleteShipment(RequestShipmentDelete $request): bool;

    public function printDocument(RequestPrint $request);

    public function updateStatuses(): int;

    public function getRetailCrmClient(): ApiClient;
}
