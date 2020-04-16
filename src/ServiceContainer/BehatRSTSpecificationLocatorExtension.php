<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BehatRSTSpecificationLocatorExtension implements Extension
{
    private const CONFIG_KEY = 'bex_rst_specification_locator';

    public function getConfigKey()
    {
        return self::CONFIG_KEY;
    }

    public function initialize(ExtensionManager $extensionManager)
    {
        // no-op
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        // no-op
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');
        $container->getDefinition(Config::class)->addArgument($config);
    }

    public function process(ContainerBuilder $container)
    {
        // no-op
    }
}
