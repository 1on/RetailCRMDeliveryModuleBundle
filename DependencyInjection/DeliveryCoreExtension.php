<?php

namespace RetailCrm\DeliveryModuleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DeliveryCoreExtension extends Extension
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
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'retailcrm.delivery_module.module_configuration',
            $config['module']
        );
        $container->setParameter(
            'retailcrm.delivery_module.module_manager.class',
            $config['module_manager_class']
        );
        $container->setParameter(
            'retailcrm.delivery_module.delivery_order.class',
            $config['delivery_order_class']
        );
    }
}
