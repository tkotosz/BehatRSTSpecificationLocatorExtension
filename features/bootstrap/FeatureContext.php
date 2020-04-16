<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\Acceptance;

use Behat\Gherkin\Node\PyStringNode;
use Bex\Behat\Context\TestRunnerContext;
use PHPUnit\Framework\Assert;

class FeatureContext extends TestRunnerContext
{
    /**
     * @Given /^I have the RST documentation:$/
     */
    public function iHaveTheRSTDocumentation(PyStringNode $content)
    {
        $file = $this->workingDirectory . '/features/my_feature.rst';
        $this->filesystem->dumpFile($file, $content->getRaw());
        $this->files[] = $file;
    }

    /**
     * @Then I should see a passing scenario
     */
    public function iShouldSeeAPassingScenario()
    {
        Assert::assertContains('1 scenario (1 passed)', $this->getStandardOutputMessage());
    }

    /**
     * @Then /^I should see (\d+) passing scenarios$/
     */
    public function iShouldSeePassingScenarios(int $count)
    {
        Assert::assertContains(sprintf('%1$d scenarios (%1$d passed)', $count), $this->getStandardOutputMessage());
    }
}
