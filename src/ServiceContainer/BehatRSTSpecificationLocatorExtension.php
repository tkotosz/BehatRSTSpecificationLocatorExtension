<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\ServiceContainer;

use Behat\Gherkin\Cache\FileCache;
use Behat\Gherkin\Cache\MemoryCache;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Bex\Behat\BehatRSTSpecificationLocatorExtension\RST\Loader\RSTFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
        $builder
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('cache')
                        ->info('Sets the rst gherkin parser cache folder')
                        ->defaultValue(
                            is_writable(sys_get_temp_dir())
                                ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat_rst_gherkin_cache'
                                : null
                        )
                    ->end()
                ->end()
        ;
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');
        $container->getDefinition(Config::class)->addArgument($config);

        if (!empty($config['cache'])) {
            $cacheDefinition = new Definition(FileCache::class, array($config['cache']));
        } else {
            $cacheDefinition = new Definition(MemoryCache::class);
        }

        $container->getDefinition(RSTFileLoader::class)->setArgument('$cache', $cacheDefinition);
    }

    public function process(ContainerBuilder $container)
    {
        // no-op
    }
}
