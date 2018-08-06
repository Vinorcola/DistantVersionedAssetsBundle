<?php

namespace Vinorcola\DistantVersionedAssetsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Vinorcola\DistantVersionedAssetsBundle\Config\Config;

class VinorcolaDistantVersionedAssetsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Inject user config in the config service.
        $definition = $container->getDefinition(Config::class);
        $definition->setArgument(0, $config);
    }
}
