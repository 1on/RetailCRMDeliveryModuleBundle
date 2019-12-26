<?php

namespace RetailCrm\DeliveryModuleBundle\Tests\Controller;

use Core\AutomateBundle\Services\Proxy;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractExchangeControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();
        parent::setUp();
    }

    /**
     * @return string
     */
    abstract protected function getExchangeControllerClassName();

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    abstract protected function getServiceMock();

    /**
     * @return array
     */
    abstract protected function dataProviderMethods();

    /**
     * @param string $method
     * @param string $type
     * @param string $dataKey
     * @param string $data
     * @param callable $assertions
     *
     * @dataProvider dataProviderMethods
     */
    public function testExchangeControllerMethods(
        string $method,
        string $type,
        string $dataKey,
        string $data,
        callable $assertions
    ) {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $req = $this->createMock(Request::class);
        $req->$type = new ParameterBag([
            'clientId' => 'clientId',
            $dataKey => $data
        ]);
        $req->expects($this->any())
            ->method('isMethod')
            ->with('post')
            ->willReturn($type == 'request');

        $reqStack = $this->createMock(RequestStack::class);
        $reqStack->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn($req);

        $res = $this->getExchangeControllerMock($reqStack)->{$method.'Action'}($req);
        $content = json_decode($res->getContent(), true);

        $assertions($res, $content);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getExchangeControllerMock($reqStack)
    {
        $container = self::$kernel->getContainer();
        $serviceMock = $this->getServiceMock();

        $exchangeControllerClassName = $this->getExchangeControllerClassName();
        $exchangeControllerMock = $this->getMockBuilder($exchangeControllerClassName)
            ->setMethods(['getDeliveryApi'])
            ->setConstructorArgs([
                $container->get('doctrine.orm.entity_manager'),
                $container->get('jms_serializer'),
                $reqStack,
            ])
            ->getMock();

        $exchangeControllerMock->expects($this->any())
            ->method('getDeliveryApi')
            ->willReturn($serviceMock);

        $exchangeControllerMock->setContainer($container);

        return $exchangeControllerMock;
    }
}
