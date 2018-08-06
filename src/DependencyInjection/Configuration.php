<?php

namespace Vinorcola\DistantVersionedAssetsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vinorcola_distant_versioned_assets');

        $rootNode
            ->fixXmlConfig('target')
            ->children()
                ->scalarNode('defaultTarget')
                    ->info('The key of the target by default.')
                    ->cannotBeEmpty()
                    ->defaultValue('default')
                ->end() // defaultTarget
                ->arrayNode('targets')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('key')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('url')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end() // url
                            ->scalarNode('manifestPath')
                                ->cannotBeEmpty()
                                ->defaultValue('build/manifest.json')
                            ->end() // manifestPath
                            ->integerNode('cacheTtl')
                                ->info('For performance reason, each manifest file is kept in cache. This option allow to configure the TTL after which the cache will be deleted (and so the manifest file will have to be fetch again).')
                                ->min(0)
                                ->defaultValue(3600)
                            ->end() // cacheTtl
                        ->end()
                    ->end()
                ->end() // targets
            ->end();

        return $treeBuilder;
    }
}
