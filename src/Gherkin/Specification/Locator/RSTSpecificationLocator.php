<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\Gherkin\Specification\Locator;

use Behat\Behat\Gherkin\Specification\LazyFeatureIterator;
use Behat\Gherkin\Filter\PathsFilter;
use Behat\Gherkin\Gherkin;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Testwork\Specification\NoSpecificationsIterator;
use Behat\Testwork\Suite\Exception\SuiteConfigurationException;
use Behat\Testwork\Suite\Suite;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Notes:
 *
 *   The whole class is copy pasted from \Behat\Behat\Gherkin\Specification\Locator\FilesystemFeatureLocator
 *
 *   Changes compared to the FilesystemFeatureLocator:
 *     - getLocatorExamples method content
 *     - 1 line change in findFeatureFiles method to look for rst files
 *
 *    Copy paste was required because the required change was in a private method.
 *
 *  @TODO Create a BaseFilesystemFeatureLocator and contribute it back to Behat to avoid copy pasting
 */
class RSTSpecificationLocator implements SpecificationLocator
{
    /** @var Gherkin */
    private $gherkin;

    /** @var string */
    private $basePath;

    public function __construct(Gherkin $gherkin, $basePath)
    {
        $this->gherkin = $gherkin;
        $this->basePath = $basePath;
    }

    public function getLocatorExamples()
    {
        return [
            'a dir <comment>(features/)</comment>',
            'a reStructuredText document <comment>(*.rst)</comment>.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function locateSpecifications(Suite $suite, $locator)
    {
        if (!$suite->hasSetting('paths')) {
            return new NoSpecificationsIterator($suite);
        }

        $suiteLocators = $this->getSuitePaths($suite);

        if ($locator) {
            $filters = array(new PathsFilter($suiteLocators));

            return new LazyFeatureIterator($suite, $this->gherkin, $this->findFeatureFiles($locator), $filters);
        }

        $featurePaths = array();
        foreach ($suiteLocators as $suiteLocator) {
            $featurePaths = array_merge($featurePaths, $this->findFeatureFiles($suiteLocator));
        }

        return new LazyFeatureIterator($suite, $this->gherkin, $featurePaths);
    }

    /**
     * Returns array of feature paths configured for the provided suite.
     *
     * @param Suite $suite
     *
     * @return string[]
     *
     * @throws SuiteConfigurationException If `paths` setting is not an array
     */
    private function getSuitePaths(Suite $suite)
    {
        if (!is_array($suite->getSetting('paths'))) {
            throw new SuiteConfigurationException(
                sprintf('`paths` setting of the "%s" suite is expected to be an array, %s given.',
                        $suite->getName(),
                        gettype($suite->getSetting('paths'))
                ),
                $suite->getName()
            );
        }

        return $suite->getSetting('paths');
    }

    /**
     * Loads feature files paths from provided path.
     *
     * @param string $path
     *
     * @return string[]
     */
    private function findFeatureFiles($path)
    {
        $absolutePath = $this->findAbsolutePath($path);

        if (!$absolutePath) {
            return array($path);
        }

        if (is_file($absolutePath)) {
            return array($absolutePath);
        }

        $iterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $absolutePath,
                    RecursiveDirectoryIterator::FOLLOW_SYMLINKS | RecursiveDirectoryIterator::SKIP_DOTS
                )
            ),
            '/^.+\.rst/i',
            RegexIterator::MATCH
        );

        $paths = array_map('strval', iterator_to_array($iterator));
        uasort($paths, 'strnatcasecmp');

        return $paths;
    }

    /**
     * Finds absolute path for provided relative (relative to base features path).
     *
     * @param string $path Relative path
     *
     * @return string
     */
    private function findAbsolutePath($path)
    {
        if (is_file($path) || is_dir($path)) {
            return realpath($path);
        }

        if (null === $this->basePath) {
            return false;
        }

        if (is_file($this->basePath . DIRECTORY_SEPARATOR . $path)
            || is_dir($this->basePath . DIRECTORY_SEPARATOR . $path)
        ) {
            return realpath($this->basePath . DIRECTORY_SEPARATOR . $path);
        }

        return false;
    }
}