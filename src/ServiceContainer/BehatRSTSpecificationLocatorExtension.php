<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\ServiceContainer;

use Behat\Gherkin\Cache\CacheInterface;
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
        // no-op
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');
        $container->getDefinition(Config::class)->addArgument($config);

        // @TODO figure out if it is possible to get access to the GherkinExtension's config
        //       if not possible then we need a separate config with a separate cache location
        $cachePath = is_writable(sys_get_temp_dir())
            ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'behat_gherkin_cache'
            : null;

        if ($cachePath) {
            $cacheDefinition = new Definition(FileCache::class, array($cachePath));
        } else {
            $cacheDefinition = new Definition(MemoryCache::class);
        }

        $container->setDefinition(CacheInterface::class, $cacheDefinition);
    }

    public function process(ContainerBuilder $container)
    {
        // no-op
    }
}
