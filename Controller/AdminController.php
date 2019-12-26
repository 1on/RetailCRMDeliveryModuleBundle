<?php

namespace Intaro\DeliveryModuleBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use RetailCrm\DeliveryModuleBundle\Entity\Connection;
use RetailCrm\DeliveryModuleBundle\Service;
use RetailCrm\DeliveryModuleBundle\Service\BaseDelivery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class AdminController extends Controller
{
    /**
     * Базовый роут
     *
     * @return string
     */
    abstract protected function getRoute();

    /**
     * Сервис для работы с апи службы доставки
     *
     * @return BaseDelivery
     */
    abstract protected function getDeliveryApi();

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var PaginatorInterface
     */
    protected $knpPaginator;

    /**
     * @var Service\OpenSsl
     */
    protected $openSsl;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * AdminController constructor.
     *
     * @param ObjectManager      $entityManager
     * @param PaginatorInterface $knpPaginator
     * @param Service\OpenSsl    $openSsl
     * @param FlashBagInterface  $flashBag
     */
    public function __construct(
        ObjectManager $entityManager,
        PaginatorInterface $knpPaginator,
        Service\OpenSsl $openSsl,
        FlashBagInterface $flashBag
    ) {
        $this->entityManager = $entityManager;
        $this->knpPaginator = $knpPaginator;
        $this->openSsl = $openSsl;
        $this->flashBag = $flashBag;
    }

    /**
     * @return string
     */
    private function getShortBundle()
    {
        return strtr('Intaro\DeliveryModuleBundle', ['\\' => '']);
    }

    /**
     * @return string
     */
    private function getNameService()
    {
        $bundle = explode('\\', 'Intaro\DeliveryModuleBundle');

        return strtr(end($bundle), ['Bundle' => '']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $clientsQuery = $this->entityManager->createQuery('
            SELECT connection
            FROM ' . $this->getConnectionClass() . ' connection
        ');

        $pagination = $this->knpPaginator->paginate(
            $clientsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render(
            $this->getShortBundle() . ':Connection:list.html.twig',
            ['pagination' => $pagination, 'route' => $this->getRoute()]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_DEVELOPER');

        $connectionClass = $this->getConnectionClass();
        $connection = new $connectionClass();
        $connection->setEncoder($this->openSsl);
        $connectionTypeClass = 'Intaro\DeliveryModuleBundle\Form\ConnectionType';
        $form = $this->createForm($connectionTypeClass, $connection, [
            'container' => $this->container,
            'is_admin' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $connection->generateClientId();
            $this->actualizeWebhooks($connection);
            $this->entityManager->persist($connection);
            $this->entityManager->flush();

            return $this->redirectToRoute($this->getRoute() . '_admin_edit', [
                'connectionId' => $connection->getId(),
            ]);
        }

        return $this->render(
            $this->getShortBundle() . ':Connection:edit.html.twig',
            ['route' => $this->getRoute(), 'form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param string  $connectionId
     *
     * @return Response
     */
    public function editAction(Request $request, $connectionId)
    {
        $connection = $this->entityManager
            ->getRepository($this->getConnectionClass())
            ->find($connectionId);
        if (null === $connection) {
            throw $this->createNotFoundException();
        }

        $connectionTypeClass = 'Intaro\DeliveryModuleBundle\Form\ConnectionType';
        $form = $this->createForm($connectionTypeClass, $connection, [
            'container' => $this->container,
            'is_admin' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->actualizeWebhooks($connection);
            $this->entityManager->flush();

            return $this->redirectToRoute($this->getRoute() . '_admin_edit', [
                'connectionId' => $connection->getId(),
            ]);
        }

        return $this->render(
            $this->getShortBundle() . ':Connection:edit.html.twig',
            [
                'route' => $this->getRoute(),
                'connection' => $connection,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param string  $connectionId
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateConfigurationAction(Request $request, $connectionId)
    {
        $this->denyAccessUnlessGranted('ROLE_DEVELOPER');

        $api = $this->getDeliveryApi();

        $connection = $this->entityManager
            ->getRepository($this->getConnectionClass())
            ->find($connectionId);

        $api->setConnection($connection);
        $result = $api->updateConfiguration();

        if (isset($result['success']) && $result['success']) {
            $this->flashBag->add('notice', 'ChangesWereSaved');
        } else {
            $this->flashBag->add('error', 'ChangesWereNotSaved');
        }

        return $this->redirectToRoute($this->getRoute() . '_admin_edit', [
            'connectionId' => $connection->getId(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function parcelListAction(Request $request)
    {
        $parcelsQuery = $this->entityManager->createQuery('
            SELECT parcel
            FROM ' . $this->getParcelClass() . ' parcel
        ');

        $pagination = $this->knpPaginator->paginate(
            $parcelsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render(
            $this->getShortBundle() . ':Parcel:list.html.twig',
            ['route' => $this->getRoute(), 'pagination' => $pagination]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function parcelNewAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_DEVELOPER');

        $parcelClass = $this->getParcelClass();
        $parcel = new $parcelClass();
        $parcelTypeClass = 'Intaro\DeliveryModuleBundle\Form\ParcelType';
        $form = $this->createForm($parcelTypeClass, $parcel, [
            'connection_class' => $this->getConnectionClass(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($parcel);
            $this->entityManager->flush();

            return $this->redirectToRoute($this->getRoute() . '_admin_parcel_list');
        }

        return $this->render(
            $this->getShortBundle() . ':Parcel:edit.html.twig',
            ['form' => $form->createView(), 'parcel' => $parcel]
        );
    }

    /**
     * @param Request $request
     * @param string  $parcelId
     *
     * @return Response
     */
    public function parcelEditAction(Request $request, $parcelId)
    {
        $parcel = $this->entityManager
            ->getRepository($this->getParcelClass())
            ->find(['id' => $parcelId]);

        $parcelTypeClass = 'Intaro\DeliveryModuleBundle\Form\ParcelType';
        $form = $this->createForm($parcelTypeClass, $parcel, [
            'connection_class' => $this->getConnectionClass(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute($this->getRoute() . '_admin_parcel_list');
        }

        return $this->render(
            $this->getShortBundle() . ':Parcel:edit.html.twig',
            ['form' => $form->createView(), 'parcel' => $parcel]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function connectAction(Request $request)
    {
        $api = $this->getDeliveryApi();

        $referer = $request->headers->get('referer');
        $account = $request->query->get('account');
        $accountUrl = null;
        if (!empty($account)) {
            $accountUrl = null === parse_url($account, PHP_URL_HOST)
                ? null : 'https://' . parse_url($account, PHP_URL_HOST);
        }

        if (
            !empty($request->request->get('clientId'))
            || !empty($request->attributes->get('clientId'))
        ) {
            if (!empty($request->request->get('clientId'))) {
                $clientId = $request->request->get('clientId');
            } else {
                $clientId = $request->attributes->get('clientId');
            }

            $connection = $this->entityManager
                ->getRepository($this->getConnectionClass())
                ->findOneBy([
                    'clientId' => $clientId,
                ]);
            $accountUrl = $connection->getCrmUrl();
        } else {
            $class = $this->getConnectionClass();
            $connection = new $class();
            $connection
                ->setLanguage($request->getLocale())
                ->setEncoder($this->openSsl);
        }

        $connectionTypeClass = 'Intaro\DeliveryModuleBundle\Form\ConnectionType';
        $form = $this->createForm($connectionTypeClass, $connection, [
            'container' => $this->container,
            'is_admin' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $connectionIsCreated = true;
            if (empty($connection->getClientId())) {
                $connection->generateClientId();
                $connectionIsCreated = false;
            }

            $api->setConnection($connection);
            $this->actualizeWebhooks($connection);
            $result = $api->updateConfiguration();
            if (isset($result['success']) && $result['success']) {
                if (!$connectionIsCreated) {
                    $this->entityManager->persist($connection);
                }
                $this->entityManager->flush();

                return $this->redirect($connection->getCrmUrl() . '/admin/integration/list');
            } else {
                $srcLogo = $request->getUriForPath(
                    '/bundles/delivery'
                    . strtolower($this->getNameService())
                    . '/images/'
                    . strtolower($this->getNameService())
                    . '.svg'
                );

                return $this->render(
                    'DeliveryCoreBundle:Connection:configure_error.html.twig',
                    [
                        'referer' => $referer,
                        'errors' => $result,
                        'title_delivery' => $this->getNameService(),
                        'src_logo_delivery' => $srcLogo,
                    ]
                );
            }
        }

        return $this->render(
            $this->getShortBundle() . ':Connection:configure.html.twig',
            [
                'route' => $this->getRoute(),
                'form' => $form->createView(),
                'account' => $accountUrl,
            ]
        );
    }

    /**
     * Actualize webhooks
     *
     * @param Connection $connection
     */
    protected function actualizeWebhooks(Connection $connection)
    {
    }
}
