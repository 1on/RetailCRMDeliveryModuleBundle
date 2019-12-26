<?php

namespace RetailCrm\DeliveryModuleBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Exception\Exception as JmsException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use RetailCrm\DeliveryModuleBundle\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Exception;
use RetailCrm\DeliveryModuleBundle\Model\IntegrationModule;
use RetailCrm\DeliveryModuleBundle\Model\RequestCalculate;
use RetailCrm\DeliveryModuleBundle\Model\RequestDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestPrint;
use RetailCrm\DeliveryModuleBundle\Model\RequestSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseCalculate;
use RetailCrm\DeliveryModuleBundle\Model\ResponseCalculateSuccessful;
use RetailCrm\DeliveryModuleBundle\Model\ResponseLoadDeliveryData;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSuccessful;
use RetailCrm\DeliveryModuleBundle\ModuleManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception as SymfonyException;

abstract class ApiController extends Controller
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var SerializerInterface
     */
    protected $jmsSerializer;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    public function __construct(
        ObjectManager $entityManager,
        SerializerInterface $jmsSerializer,
        ModuleManager $moduleManager,
        DeliveryOrderManager $deliveryOrderManager,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->jmsSerializer = $jmsSerializer;
        $this->moduleManager = $moduleManager;
        $this->deliveryOrderManager = $deliveryOrderManager;

        $request = $requestStack->getCurrentRequest();
        if ($request->isMethod('post')) {
            $clientId = $request->request->get('clientId');
        } else {
            $clientId = $request->query->get('clientId');
        }

        $account = $entityManager->getRepository(Account::class)
            ->findOneBy(['id' => $clientId]);
        if (null === $account) {
            return $this->getInvalidResponse('ClientId not found', 404);
        }

        $this->moduleManager->setAccount($account);
    }

    public function activityAction(Request $request): JsonResponse
    {
        $activity = $request->request->get('activity');

        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $activity,
                IntegrationModule::class,
                'json',
                DeserializationContext::create()->setGroups(['activity'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $this->account->setActive($requestModel->active);
        $this->account->setFreeze($requestModel->freeze);

        $systemUrl = $request->request->get('systemUrl');
        $this->account->setCrmUrl($systemUrl);

        $this->entityManager->flush();

        return $this->getSucessfullResponse();
    }

    public function calculateAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('calculate');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestCalculate::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        try {
            $responseModel = $this->doCalculate($requestModel);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse($responseModel);
    }

    public function saveAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('save');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestSave::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $delivery = $this->deliveryOrderManager->findOneBy([
            'account' => $this->account,
            'orderId' => $requestModel->order,
        ]);

        // ищем доставки, созданные без заказа в запросе get
        if (null === $delivery) {
            $parcel = $this->deliveryOrderManager
                ->findOneBy(['account' => $this->account, 'trackId' => $requestModel->deliveryId]);
        }

        try {
            $responseModel = $this->doSave($requestModel, $delivery);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (\InvalidArgumentException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        if (null === $delivery) {
            $deliveryClass = $this->deliveryOrderManager->create();
            $delivery = new $deliveryClass();
        }

        $delivery
            ->setAccount($this->moduleManager->getAccount())
            ->setOrderId($requestModel->order)
            ->setExternalId($responseModel->deliveryId)
            ->setTrackNumber($responseModel->trackNumber)
        ;

        if (is_array($responseModel->additionalData)) {
            foreach ($responseModel->additionalData as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (is_callable([$delivery, $setter])) {
                    $delivery->$setter($value);
                }
            }
        }

        if (empty($delivery->getId())) {
            $this->entityManager->persist($delivery);
        }
        $this->entityManager->flush();

        return $this->getSucessfullResponse($responseModel);
    }

    public function getAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $externalId = $request->query->get('deliveryId');
        if (null === $externalId || empty($externalId)) {
            return $this->getInvalidResponse('DeliveryId is required', 400);
        }

        try {
            $responseModel = $this->doGet($externalId);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse($responseModel);
    }

    public function deleteAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('delete');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestDelete::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $delivery = $this->deliveryOrderManager->findOneBy([
            'account' => $this->account,
            'externalId' => $requestModel->deliveryId,
        ]);

        if (null === $delivery) {
            return $this->getInvalidResponse("Delivery {$requestModel->deliveryId} not found", 404);
        }

        try {
            $this->doDelete($requestModel, $delivery);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $this->entityManager->remove($delivery);
        $this->entityManager->flush();

        return $this->getSucessfullResponse();
    }

    public function printAction(Request $request): Response
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('print');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestPrint::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        try {
            $plateData = $this->doPrint($requestModel);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 500);
        }

        if (is_array($plateData)) {
            $tmpFilename = tempnam(sys_get_temp_dir(), 'zip');
            $labelArchive = new \ZipArchive();
            $labelArchive->open($tmpFilename, \ZipArchive::CREATE);
            foreach ($plateData as $fileName => $plate) {
                $labelArchive->addFromString($fileName, $plate);
            }
            $labelArchive->close();
            $contents = file_get_contents($tmpFilename);
            unlink($tmpFilename);

            $response = new Response($contents);
            $response->headers->set('Content-Type', 'application/zip');
        } else {
            $response = new Response($plateData);
            $response->headers->set('Content-Type', 'application/pdf');
        }

        return $response;
    }

    public function shipmentSaveAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('shipmentSave');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestShipmentSave::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        try {
            $responseModel = $this->doShipmentSave($requestModel);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (\InvalidArgumentException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse($responseModel);
    }

    public function shipmentDeleteAction(Request $request): JsonResponse
    {
        if (
            false === $this->moduleManager->getAccount()->getIsActive()
            || false !== $this->moduleManager->getAccount()->getIsFreeze()
        ) {
            return $this->getInvalidResponse('Account is not active', 403);
        }

        $requestData = $request->request->get('shipmentDelete');
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestShipmentDelete::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        try {
            $this->doShipmentDelete($requestModel);
        } catch (Exception\ValidationException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        } catch (Exception\ApiException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse();
    }

    protected function getSucessfullResponse($responseResult = null): JsonResponse
    {
        if (is_array($responseResult)) {
            $response = new ResponseCalculateSuccessful();
        } else {
            $response = new ResponseSuccessful();
        }
        $response->result = $responseResult;

        $responseData = $this->jmsSerializer
            ->serialize($response, 'json', SerializationContext::create()->setGroups(['response']));

        return new JsonResponse(json_decode($responseData, true));
    }

    protected function getInvalidResponse(string $message, int $statusCode): JsonResponse
    {
        if ($statusCode >= 500) {
            //корректно отдается в crm только в окружении prod
            throw new SymfonyException\HttpException(
                $statusCode,
                json_encode(['success' => false, 'errorMsg' => $message])
            );
        }

        return new JsonResponse([
            'success' => false,
            'errorMsg' => $message,
        ], $statusCode);
    }

    protected function doCalculate(RequestCalculate $requestModel): ResponseCalculate
    {
        return $this->moduleManager->calculateDelivery($requestModel);
    }

    protected function doGet(string $externalId): ResponseLoadDeliveryData
    {
        return $this->moduleManager->getDelivery($externalId);
    }

    protected function doSave(RequestSave $requestModel, DeliveryOrder $delivery = null): ResponseSave
    {
        return $this->moduleManager->saveDelivery($requestModel, $delivery);
    }

    protected function doDelete(RequestDelete $requestModel, DeliveryOrder $delivery): bool
    {
        return $this->moduleManager->deleteDelivery($requestModel, $delivery);
    }

    protected function doShipmentSave(RequestShipmentSave $requestModel): ResponseShipmentSave
    {
        return $this->moduleManager->saveShipment($requestModel);
    }

    protected function doShipmentDelete(RequestShipmentDelete $requestModel): bool
    {
        return $this->moduleManager->deleteShipment($requestModel);
    }

    protected function doPrint(RequestPrint $requestModel)
    {
        return $this->moduleManager->printDocument($requestModel);
    }
}
