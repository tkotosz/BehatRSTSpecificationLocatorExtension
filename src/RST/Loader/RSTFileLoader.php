<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\RST\Loader;

use Behat\Gherkin\Cache\CacheInterface;
use Behat\Gherkin\Loader\AbstractFileLoader;
use Behat\Gherkin\Node\FeatureNode;
use Bex\Behat\BehatRSTSpecificationLocatorExtension\RST\Parser as RSTParser;

/**
 * Notes:
 *
 *   It was necessary to extend the AbstractFileLoader instead of implementing \Behat\Gherkin\Loader\FileLoaderInterface
 *   because I had to get access to the findAbsolutePath method.
 *
 *   Fun fact: The findAbsolutePath method is the exact same method
 *   as the one in \Behat\Behat\Gherkin\Specification\Locator\FilesystemFeatureLocator
 *   so it is already duplicated in the main project.
 *
 *   @TODO Create some kind of service which exposes a public findAbsolutePath and contribute it back to Behat
 *         to avoid copy-pasting and relying on implementation details like this AbstractFileLoader
 */
class RSTFileLoader extends AbstractFileLoader
{
    /** @var RSTParser */
    private $rstParser;

    /** @var CacheInterface */
    private $cache;

    public function __construct(RSTParser $rstParser, CacheInterface $cache, string $basePath)
    {
        $this->rstParser = $rstParser;
        $this->cache = $cache;
        $this->basePath = $basePath;
    }

    public function supports($path)
    {
        return is_string($path)
            && is_file($absolute = $this->findAbsolutePath($path))
            && 'rst' === pathinfo($absolute, PATHINFO_EXTENSION);
    }

    /**
     * Loads features from provided resource (logic is based on GherkinFileLoader)
     *
     * @param string $path Resource to load
     *
     * @return FeatureNode[]
     */
    public function load($path)
    {
        $path = $this->findAbsolutePath($path);

        if ($this->cache->isFresh($path, filemtime($path))) {
            $feature = $this->cache->read($path);
        } elseif (null !== $feature = $this->parseFeature($path)) {
            $this->cache->write($path, $feature);
        }

        return null !== $feature ? [$feature] : [];
    }

    private function parseFeature(string $path): ?FeatureNode
    {
        return $this->rstParser->parse(file_get_contents($path), $path);
    }
}