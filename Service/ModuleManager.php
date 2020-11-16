<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use RetailCrm\ApiClient;
use RetailCrm\DeliveryModuleBundle\Exception\NotFoundException;
use RetailCrm\DeliveryModuleBundle\Model\Configuration;
use RetailCrm\DeliveryModuleBundle\Model\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Model\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Model\IntegrationModule;
use RetailCrm\DeliveryModuleBundle\Model\RequestCalculate;
use RetailCrm\DeliveryModuleBundle\Model\RequestDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestPrint;
use RetailCrm\DeliveryModuleBundle\Model\RequestSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentPointList;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestStatusUpdateItem;
use RetailCrm\DeliveryModuleBundle\Model\ResponseLoadDeliveryData;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\Terminal;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class ModuleManager implements ModuleManagerInterface
{
    /**
     * @var array
     */
    protected $moduleParameters;

    /**
     * @var RetailCrmClientFactoryInterface
     */
    protected $retailCrmClientFactory;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var DeliveryOrderManager
     */
    protected $deliveryManager;

    /**
     * @var SerializerInterface
     */
    protected $jmsSerializer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var PinbaService
     */
    protected $pinbaService;

    /**
     * @var ApiClient
     */
    private $retailCrmClient;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        array $moduleParameters,
        RetailCrmClientFactoryInterface $retailCrmClientFactory,
        DeliveryOrderManager $deliveryManager,
        SerializerInterface $jmsSerializer,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router
    ) {
        $this->moduleParameters = $moduleParameters;
        $this->retailCrmClientFactory = $retailCrmClientFactory;
        $this->deliveryManager = $deliveryManager;
        $this->jmsSerializer = $jmsSerializer;
        $this->translator = $translator;
        $this->router = $router;
        $this->pinbaService = new PinbaService();
    }

    public function getIntegrationCode(): string
    {
        return $this->moduleParameters['integration_code'];
    }

    public function getAccountCode(): string
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        return sprintf('%s-%s', $this->getIntegrationCode(), $this->account->getId());
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): ModuleManagerInterface
    {
        $this->account = $account;

        if ($this->account && $this->account->getLanguage() && $this->translator) {
            $this->translator->setLocale($this->account->getLanguage());
        }

        $this->retailCrmClient = $this->retailCrmClientFactory->createRetailCrmClient($this->account);

        return $this;
    }

    public function updateModuleConfiguration(): bool
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        $integrationModule = $this->jmsSerializer
            ->serialize(
                $this->buildIntegrationModule(),
                'json',
                SerializationContext::create()->setGroups(['get', 'request'])->setSerializeNull(true)
            );

        $client = $this->retailCrmClient;
        $response = $this->pinbaService->timerHandler(
            [
                'api' => 'retailCrm',
                'method' => 'integrationModulesEdit',
            ],
            static function () use ($client, $integrationModule) {
                return $client->request->integrationModulesEdit(
                    json_decode($integrationModule, true)
                );
            }
        );

        if ($response['success'] ?? false) {
            return true;
        } else {
            if ($this->logger) {
                $errorMsg = $response['errorMsg'] ?? '';
                $errors = json_encode($response['errors'] ?? '');
                $this->logger->warning("Failed to update module configuration[account={$this->getAccount()->getCrmUrl()}]: {$errorMsg}. Detailed errors: {$errors}");
            }

            return false;
        }
    }

    protected function buildIntegrationModule(): IntegrationModule
    {
        $integrationModule = new IntegrationModule();

        $integrationModule->code = $this->getAccountCode();
        $integrationModule->integrationCode = $this->getIntegrationCode();
        $integrationModule->active = $this->account->isActive();
        $integrationModule->name = $this->moduleParameters['locales'][$this->translator->getLocale()]['name'];
        $integrationModule->logo = $this->moduleParameters['locales'][$this->translator->getLocale()]['logo'];
        $integrationModule->clientId = $this->account->getClientId();
        $integrationModule->availableCountries = $this->moduleParameters['countries'];
        $integrationModule->actions = [
            'activity' => 'activity',
        ];

        $integrationModule->baseUrl = $this->router->generate(
            'retailcrm_delivery_module_api_base',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $integrationModule->accountUrl = $this->getAccountUrl();

        $integrationModule->integrations = ['delivery' => $this->doBuildConfiguration()];

        return $integrationModule;
    }

    abstract protected function doBuildConfiguration(): Configuration;

    abstract protected function getAccountUrl(): string;

    public function calculateDelivery(RequestCalculate $data): array
    {
        throw new \LogicException('Method should be implemented');
    }

    public function saveDelivery(RequestSave $data, DeliveryOrder $delivery = null): ResponseSave
    {
        throw new \LogicException('Method should be implemented');
    }

    public function getDelivery(string $externalId): ResponseLoadDeliveryData
    {
        throw new \LogicException('Method should be implemented');
    }

    public function deleteDelivery(RequestDelete $request, DeliveryOrder $delivery): bool
    {
        throw new \LogicException('Method should be implemented');
    }

    /**
     * @return Terminal[]
     */
    public function getShipmentPointList(RequestShipmentPointList $request): array
    {
        throw new \LogicException('Method should be implemented');
    }

    public function saveShipment(RequestShipmentSave $data): ResponseShipmentSave
    {
        throw new \LogicException('Method should be implemented');
    }

    public function deleteShipment(RequestShipmentDelete $request): bool
    {
        throw new \LogicException('Method should be implemented');
    }

    public function printDocument(RequestPrint $request)
    {
        $deliveries = $this->deliveryManager->findBy([
            'account' => $this->account,
            'externalId' => $request->deliveryIds,
        ]);

        if (empty($deliveries)) {
            throw new NotFoundException('Deliveries not found');
        }

        return $this->doPrint($request->type, $deliveries);
    }

    protected function doPrint(string $documentType, array $deliveries): array
    {
        throw new \LogicException('Method should be implemented');
    }

    public function updateStatuses(): int
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        $deliveryQuery = $this->deliveryManager->createQueryBuilder('delivery')
            ->select('delivery')
            ->andWhere('delivery.account = :account')
            ->andWhere('delivery.id >= :lastId')
            ->andWhere('delivery.ended = FALSE')
            ->orderBy('delivery.id ASC')
            ->createQuery()
            ->setMaxResults(static::STATUS_UPDATE_LIMIT)
            ->setAccount($this->account)
        ;

        $count = 0;
        $lastId = 0;
        while (true) {
            $deliveryQuery->setParameter('lastId', $lastId);
            $deliveries = $deliveryQuery->getResult();
            if (empty($deliveries)) {
                break;
            }

            foreach ($deliveries as $delivery) {
                if ($delivery->getId() > $lastId) {
                    $lastId = $delivery->getId();
                }
            }

            $deliveriesHistory = $this->doUpdateStatuses($deliveries);
            if (!empty($deliveriesHistory)) {
                $this->updateRetailCrmOrderStatuses($deliveriesHistory);
            }
            $count += count($deliveriesHistory);
            $this->deliveryManager->flush();
        }

        return $count;
    }

    public function getRetailCrmClient(): ApiClient
    {
        if (null === $this->retailCrmClient) {
            throw new \LogicException('Account is not selected');
        }

        return $this->retailCrmClient;
    }

    /**
     * Получение актуальных статусов доставки от службы доставки.
     *
     * @param array $deliveries
     *
     * @return RequestStatusUpdateItem[]
     */
    protected function doUpdateStatuses(array $deliveries): array
    {
        throw new \LogicException('Method should be implemented');
    }

    /**
     * Обновление статусов в CRM.
     *
     * @param RequestStatusUpdateItem[] $deliveriesHistory
     *
     * @throws \Exception
     */
    protected function updateRetailCrmOrderStatuses(array $deliveriesHistory): void
    {
        if (count($deliveriesHistory) > 100) {
            $parts = array_chunk($deliveriesHistory, 100);
        } else {
            $parts = [$deliveriesHistory];
        }

        foreach ($parts as $part) {
            $request = $this->jmsSerializer
                ->serialize($part, 'json', SerializationContext::create()->setGroups(['get', 'request']));

            $client = $this->retailCrmClient;
            $moduleCode = $this->getAccountCode();
            $this->pinbaService->timerHandler(
                [
                    'api' => 'retailCrm',
                    'method' => 'deliveryTracking',
                ],
                static function () use ($client, $moduleCode, $request) {
                    return $client->request->deliveryTracking(
                        $moduleCode,
                        json_decode($request, true)
                    );
                }
            );
        }
    }
}
