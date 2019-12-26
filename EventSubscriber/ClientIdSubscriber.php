<?php

namespace RetailCrm\DeliveryModuleBundle\EventSubscriber;

use Ramsey\Uuid\Uuid;
use RetailCrm\DeliveryModuleBundle\Controller\ClientIdSecuredControllerInterface;
use RetailCrm\DeliveryModuleBundle\Service\AccountManager;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ClientIdSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    public function __construct(AccountManager $accountManager, ModuleManagerInterface $moduleManager)
    {
        $this->accountManager = $accountManager;
        $this->moduleManager = $moduleManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (!$controller instanceof ClientIdSecuredControllerInterface) {
            return;
        }

        $request = $event->getRequest();

        if ($request->isMethod('post')) {
            $clientId = $request->request->get('clientId');
        } else {
            $clientId = $request->query->get('clientId');
        }
        if (empty($clientId)) {
            throw new AccessDeniedHttpException('ClientId required');
        }

        if (!Uuid::isValid($clientId)) {
            throw new AccessDeniedHttpException('ClientId is not valid');
        }

        $account = $this->accountManager->findOneBy(['clientId' => $clientId]);
        if (null === $account) {
            throw new AccessDeniedHttpException('ClientId not found');
        }
        if (!$account->isActive()) {
            throw new AccessDeniedHttpException('Account is not active');
        }
        if ($account->isFreeze()) {
            throw new AccessDeniedHttpException('Account is freezed');
        }

        $this->moduleManager->setAccount($account);
    }
}
