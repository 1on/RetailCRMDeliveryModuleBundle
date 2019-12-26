<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use RetailCrm\ApiClient;
use RetailCrm\DeliveryModuleBundle\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Model\Configuration;
use RetailCrm\DeliveryModuleBundle\Model\IntegrationModule;
use RetailCrm\DeliveryModuleBundle\Model\RequestCalculate;
use RetailCrm\DeliveryModuleBundle\Model\RequestDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestPrint;
use RetailCrm\DeliveryModuleBundle\Model\RequestSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseCalculate;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentSave;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class ModuleManager
{
    const STATUS_UPDATE_LIMIT = 100;

    /**
     * @var string
     */
    protected $integrationCode;

    /**
     * @var array
     */
    private $config;

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
    private $logger;

    public function __construct(
        array $moduleConfig,
        DeliveryOrderManager $deliveryManager,
        SerializerInterface $jmsSerializer,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router,
        PinbaService $pinbaService
    ) {
        $this->integrationCode = $moduleConfig['integrationCode'];
        $this->config = $moduleConfig;
        $this->deliveryManager = $deliveryManager;
        $this->jmsSerializer = $jmsSerializer;
        $this->translator = $translator;
        $this->router = $router;
        $this->pinbaService = $pinbaService;
    }

    public function getModuleCode(): string
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        return sprintf('%s-%s', $this->integrationCode, $this->account->getId());
    }

    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        if ($this->account && $this->account->getLanguage() && $this->translator) {
            $this->translator->setLocale($this->account->getLanguage());
        }

        $this->createRetailCrmClient();

        return $this;
    }

    public function updateModuleConfiguration(): bool
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        $integrationModule = $this->buildIntegrationModule();
        $integrationModule = $this->jmsSerializer
            ->serialize($integrationModule, 'json', SerializationContext::create()->setGroups(['get', 'request']));

        $response = $this->pinbaService->timerHandler(
            [
                'api' => 'retailCrm',
                'method' => 'integrationModulesEdit',
            ],
            static function () use ($integrationModule) {
                return $this->retailCrmClient->request->integrationModulesEdit(
                    json_decode($integrationModule, true)
                );
            }
        );

        if ($result['success'] ?? false) {
            return true;
        } else {
            return false;
        }
    }

    protected function buildIntegrationModule(): IntegrationModule
    {
        $integrationModule = new IntegrationModule();

        $integrationModule->code = $this->getModuleCode();
        $integrationModule->integrationCode = $this->integrationCode;
        $integrationModule->active = $this->account->isActive();
        $integrationModule->name = $this->config['locales'][$this->translator->getLocale()]['name'];
        $integrationModule->logo = $this->config['locales'][$this->translator->getLocale()]['logo'];
        $integrationModule->clientId = $this->account->getId();
        $integrationModule->availableCountries = $this->config['countries'];
        $integrationModule->actions = [
            'activity' => 'activity',
        ];

        $integrationModule->baseUrl = $this->router->generate(
            'retailcrm_delivery_module_exchange_base',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $integrationModule->accountUrl = $this->router->generate(
            'retailcrm_delivery_module_connect',
            ['_locale' => $this->connection->getLanguage()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $integrationModule->integrations['delivery'] = $this->doBuildDeliveryConfiguration();

        return $integrationModule;
    }

    abstract protected function doBuildConfiguration(): Configuration;

    public function calculateDelivery(RequestCalculate $data): ResponseCalculate
    {
        throw new \LogicException('Method should be implemented');
    }

    public function saveDelivery(RequestSave $data, DeliveryOrder $delivery = null): ResponseSave
    {
        throw new \LogicException('Method should be implemented');
    }

    public function deleteDelivery(RequestDelete $request, DeliveryOrder $delivery): bool
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
            throw new Exception\ApiException('Deliveries not found.');
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

    /**
     * Получение актуальных статусов доставки от службы доставки
     *
     * @param \RetailCrm\DeliveryModuleBundle\Entity\Parcel[] $parces
     *
     * @return \RetailCrm\DeliveryModuleBundle\Model\RequestStatusUpdateItem[]
     */
    protected function doUpdateStatuses(array $deliveries): array
    {
        throw new \LogicException('Method should be implemented');
    }

    /**
     * Обновление статусов в CRM
     *
     * @param \RetailCrm\DeliveryModuleBundle\Model\RequestStatusUpdateItem[] $deliveriesHistory
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

            $response = $this->pinbaService->timerHandler(
                [
                    'api' => 'retailCrm',
                    'method' => 'deliveryTracking',
                ],
                static function () use ($request) {
                    return $this->retailCrmClient->request->deliveryTracking(
                        $this->getModuleCode(),
                        json_decode($request, true)
                    );
                }
            );
        }
    }

    private function createRetailCrmClient(): void
    {
        if (null === $this->account) {
            throw new \LogicException('Account is not selected');
        }

        if (empty($this->account->getCrmUrl())) {
            throw new \LogicException('Crm url is empty');
        }

        if (empty($this->account->getCrmApiKey())) {
            throw new \LogicException('Crm apiKey is empty');
        }

        $this->retailCrmClient = new ApiClient(
            $this->account->getCrmUrl(),
            $this->account->getCrmApiKey(),
            ApiClient::V5
        );
        if ($this->logger) {
            $this->retailCrmClient->setLogger($this->logger);
        }
    }
}
