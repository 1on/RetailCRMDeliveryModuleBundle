<?php

namespace RetailCrm\DeliveryModuleBundle\Controller;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Exception\Exception as JmsException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use RetailCrm\DeliveryModuleBundle\Exception;
use RetailCrm\DeliveryModuleBundle\Model\AbstractResponseSuccessful;
use RetailCrm\DeliveryModuleBundle\Model\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Model\IntegrationModule;
use RetailCrm\DeliveryModuleBundle\Model\RequestCalculate;
use RetailCrm\DeliveryModuleBundle\Model\RequestDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestPrint;
use RetailCrm\DeliveryModuleBundle\Model\RequestSave;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentDelete;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentPointList;
use RetailCrm\DeliveryModuleBundle\Model\RequestShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseCalculateSuccessful;
use RetailCrm\DeliveryModuleBundle\Model\ResponseLoadDeliveryData;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentPointListSuccessful;
use RetailCrm\DeliveryModuleBundle\Model\ResponseShipmentSave;
use RetailCrm\DeliveryModuleBundle\Model\ResponseSuccessful;
use RetailCrm\DeliveryModuleBundle\Service\AccountManager;
use RetailCrm\DeliveryModuleBundle\Service\DeliveryOrderManager;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManager;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception as SymfonyException;

class ApiController extends AbstractController implements ClientIdSecuredControllerInterface
{
    /**
     * @var SerializerInterface
     */
    protected $jmsSerializer;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var AccountManager
     */
    protected $accountManager;

    /**
     * @var DeliveryOrderManager
     */
    protected $deliveryOrderManager;

    public function __construct(
        SerializerInterface $jmsSerializer,
        ModuleManagerInterface $moduleManager,
        AccountManager $accountManager,
        DeliveryOrderManager $deliveryOrderManager
    ) {
        $this->jmsSerializer = $jmsSerializer;
        $this->moduleManager = $moduleManager;
        $this->accountManager = $accountManager;
        $this->deliveryOrderManager = $deliveryOrderManager;
    }

    public function activity(Request $request): JsonResponse
    {
        $requestData = $request->request->get('activity');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "activity" must be json', 400);
        }

        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                IntegrationModule::class,
                'json',
                DeserializationContext::create()->setGroups(['activity'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $this->moduleManager->getAccount()->setActive($requestModel->active);
        $this->moduleManager->getAccount()->setFreeze($requestModel->freeze);
        $this->moduleManager->getAccount()->setCrmUrl($request->request->get('systemUrl'));

        $this->accountManager->flush();

        return $this->getSucessfullResponse();
    }

    public function calculate(Request $request): JsonResponse
    {
        $requestData = $request->request->get('calculate');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "calculate" must be json', 400);
        }

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
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $response = new ResponseCalculateSuccessful($responseModel);
        $response->result = $responseModel;

        return $this->getSucessfullResponse($response);
    }

    public function save(Request $request): JsonResponse
    {
        $requestData = $request->request->get('save');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "save" must be json', 400);
        }

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
        if (null === $requestModel->delivery) {
            return $this->getInvalidResponse('Invalid request format', 400);
        }

        $delivery = $this->deliveryOrderManager->findOneBy([
            'account' => $this->moduleManager->getAccount(),
            'orderId' => $requestModel->order,
        ]);

        // ищем доставки, созданные без заказа в запросе get
        if (null === $delivery) {
            $delivery = $this->deliveryOrderManager
                ->findOneBy([
                    'account' => $this->moduleManager->getAccount(),
                    'externalId' => $requestModel->deliveryId,
                ]);
        }

        try {
            $responseModel = $this->doSave($requestModel, $delivery);
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        if (null === $delivery) {
            $delivery = $this->deliveryOrderManager->create();
        }

        $delivery
            ->setAccount($this->moduleManager->getAccount())
            ->setOrderId($requestModel->order)
            ->setExternalId($responseModel->deliveryId)
        ;
        if ($responseModel->trackNumber) {
            $delivery->setTrackNumber($responseModel->trackNumber);
        }

        if (is_array($responseModel->additionalData)) {
            foreach ($responseModel->additionalData as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (is_callable([$delivery, $setter])) {
                    $delivery->$setter($value);
                }
            }
        }

        if (empty($delivery->getId())) {
            $this->deliveryOrderManager->persist($delivery);
        }
        $this->deliveryOrderManager->flush();

        return $this->getSucessfullResponse($responseModel);
    }

    public function getDeliveryOrder(Request $request): JsonResponse
    {
        $externalId = $request->query->get('deliveryId');
        if (null === $externalId || empty($externalId)) {
            return $this->getInvalidResponse('DeliveryId is required', 400);
        }

        try {
            $responseModel = $this->doGet($externalId);
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse($responseModel);
    }

    public function delete(Request $request): JsonResponse
    {
        $requestData = $request->request->get('delete');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "delete" must be json', 400);
        }

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
            'account' => $this->moduleManager->getAccount(),
            'externalId' => $requestModel->deliveryId,
        ]);

        if (null === $delivery) {
            return $this->getInvalidResponse("Delivery '{$requestModel->deliveryId}' not found", 404);
        }

        try {
            $this->doDelete($requestModel, $delivery);
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $this->deliveryOrderManager->remove($delivery);
        $this->deliveryOrderManager->flush();

        return $this->getSucessfullResponse();
    }

    public function shipmentPointList(Request $request): JsonResponse
    {
        $requestData = json_encode($request->query->all());
        try {
            $requestModel = $this->jmsSerializer->deserialize(
                $requestData,
                RequestShipmentPointList::class,
                'json',
                DeserializationContext::create()->setGroups(['get', 'request'])
            );
        } catch (JmsException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        try {
            $responseModel = $this->doShipmentPointList($requestModel);
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        $response = new ResponseShipmentPointListSuccessful($responseModel);
        $response->result = $responseModel;

        return $this->getSucessfullResponse($response);
    }

    public function print(Request $request): Response
    {
        $requestData = $request->request->get('print');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "print" must be json', 400);
        }

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
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\NotFoundException $e) {
            return $this->getInvalidResponse($e->getMessage(), 404);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        if (count($plateData) > 1) {
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
            $response = new Response(reset($plateData));
            $response->headers->set('Content-Type', 'application/pdf');
        }

        return $response;
    }

    public function shipmentSave(Request $request): JsonResponse
    {
        $requestData = $request->request->get('shipmentSave');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "shipmentSave" must be json', 400);
        }

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
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse($responseModel);
    }

    public function shipmentDelete(Request $request): JsonResponse
    {
        $requestData = $request->request->get('shipmentDelete');
        if (!is_string($requestData)) {
            return $this->getInvalidResponse('Parameter "shipmentDelete" must be json', 400);
        }

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
        } catch (Exception\ServerUnreachableException $e) {
            return $this->getInvalidResponse($e->getMessage(), 521);
        } catch (Exception\AbstractModuleException $e) {
            return $this->getInvalidResponse($e->getMessage(), 400);
        }

        return $this->getSucessfullResponse();
    }

    protected function getSucessfullResponse(object $responseResult = null): JsonResponse
    {
        if (!$responseResult instanceof AbstractResponseSuccessful) {
            $response = new ResponseSuccessful();
            $response->result = $responseResult;
        } else {
            $response = $responseResult;
        }

        $responseData = $this->jmsSerializer
            ->serialize($response, 'json', SerializationContext::create()->setGroups(['response']));

        return new JsonResponse(json_decode($responseData, true));
    }

    protected function getInvalidResponse(string $message, int $statusCode): JsonResponse
    {
        if ($statusCode >= 500) {
            //корректно отдается в crm только в окружении prod
            throw new SymfonyException\HttpException($statusCode, json_encode(['success' => false, 'errorMsg' => $message]));
        }

        return new JsonResponse([
            'success' => false,
            'errorMsg' => $message,
        ], $statusCode);
    }

    protected function doCalculate(RequestCalculate $requestModel): array
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

    protected function doShipmentPointList(RequestShipmentPointList $requestModel): array
    {
        return $this->moduleManager->getShipmentPointList($requestModel);
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
