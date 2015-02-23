<?php

namespace Qandidate\AuthorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
    * {@inheritdoc}
    */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;

        $rootNode = $treeBuilder->root('qandidate_author');

        $rootNode
            ->children()
                ->arrayNode('paths')
                    ->defaultValue(array('_authors'))
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('layout')->defaultValue('author')->end()
                ->scalarNode('permalink')->defaultValue('authors/:filename/')->end()
            ->end();

        return $treeBuilder;
    }
}
