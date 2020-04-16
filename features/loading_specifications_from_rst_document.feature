Feature: Loading Specifications from RST document

  Scenario: Loading and RST document with a single code block
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\BehatRSTSpecificationLocatorExtension: ~
      """
    And I have the RST documentation:
      """
      My awesome feature
      ==================

      This feature is the best one in the world. This document contains many things about it. Have fun reading it.

      For example it can perform magic, e.g.:

      .. code-block:: gherkin

        Scenario:
          Given I have a step
          When I have another step
          Then I am happy

      Also it can do other stuff as well. Doc will be updated later. :)
      """
    And I have the context:
      """
      <?php

      use Behat\Behat\Context\Context;

      class FeatureContext implements Context
      {
          /**
           * @Given I have a step
           */
          function passingStep() {}

          /**
           * @When I have another step
           */
          function anotherPassingStep() {}

          /**
           * @Then I am happy
           */
          function iAmHappy() {}
      }
      """
    When I run Behat
    Then I should see a passing scenario