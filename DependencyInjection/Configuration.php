<?php

namespace RetailCrm\DeliveryModuleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    protected static $availableLocales = ['ru', 'en', 'es'];

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('retailcrm_delivery_module');

        $rootNode
            ->arrayNode('module')
                ->scalarNode('integration_code')
                    ->isRequired()
                ->end()
                ->arrayNode('locales')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('locale')

                    ->prototype('array')
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                        ->end()
                        ->scalarNode('logo')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('countries')
                    ->cannotBeEmpty()
                    ->defaultValue(['ru'])
                ->end()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('module_manager_class')
                ->isRequired()
            ->end()
            ->scalarNode('delivery_order_class')
                ->isRequired()
            ->end()
        ;

        return $treeBuilder;
    }
}
