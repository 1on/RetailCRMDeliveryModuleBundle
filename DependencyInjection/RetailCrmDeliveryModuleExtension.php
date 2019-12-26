<?php

namespace RetailCrm\DeliveryModuleBundle\DependencyInjection;

use RetailCrm\DeliveryModuleBundle\Model\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Model\Entity\DeliveryOrder;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RetailCrmDeliveryModuleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'retailcrm.delivery_module.configuration',
            $config['configuration']
        );

        $moduleManagerClass = $config['module_manager_class'];
        if (!class_exists($moduleManagerClass)) {
            throw new \InvalidArgumentException("module_manager_class '{$moduleManagerClass}' does not exists");
        }
        if (!is_subclass_of($moduleManagerClass, ModuleManagerInterface::class)) {
            throw new \InvalidArgumentException("module_manager_class '{$moduleManagerClass}' must implement ModuleManagerInterace");
        }
        $container->setParameter(
            'retailcrm.delivery_module.module_manager.class',
            $moduleManagerClass
        );

        $accountClass = $config['account_class'];
        if (!class_exists($accountClass)) {
            throw new \InvalidArgumentException("account_class'] '{$accountClass}' does not exists");
        }
        if (!is_subclass_of($accountClass, Account::class)) {
            throw new \InvalidArgumentException("account_class '{$accountClass}' must extend " . Account::class);
        }
        $container->setParameter(
            'retailcrm.delivery_module.account.class',
            $accountClass
        );

        $deliveryOrderClass = $config['delivery_order_class'];
        if (!class_exists($deliveryOrderClass)) {
            throw new \InvalidArgumentException("delivery_order_class'] '{$deliveryOrderClass}' does not exists");
        }
        if (!is_subclass_of($deliveryOrderClass, DeliveryOrder::class)) {
            throw new \InvalidArgumentException("delivery_order_class '{$deliveryOrderClass}' must extend " . DeliveryOrder::class);
        }
        $container->setParameter(
            'retailcrm.delivery_module.delivery_order.class',
            $config['delivery_order_class']
        );
    }
}
