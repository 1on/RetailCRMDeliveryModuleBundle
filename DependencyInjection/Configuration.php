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
        $treeBuilder = new TreeBuilder('retail_crm_delivery_module');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('module_manager_class')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('account_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('delivery_order_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()

                ->arrayNode('configuration')
                    ->children()
                        ->scalarNode('integration_code')
                            ->cannotBeEmpty()
                        ->end()

                        ->arrayNode('countries')
                            ->prototype('scalar')->end()
                            ->requiresAtLeastOneElement()
                            ->defaultValue(['ru'])
                        ->end()

                        ->arrayNode('locales')
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('locale')

                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('logo')
                                        ->isRequired()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->variableNode('parameters')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
