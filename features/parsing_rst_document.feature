Feature: Parsing RST document

  Background:
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\BehatRSTSpecificationLocatorExtension: ~
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

  Scenario: Parsing an RST document with a single code block
    Given I have the RST documentation:
      """
      My awesome feature
      ==================

      This feature is the best one in the world. This document contains many things about it. Have fun reading it.

      For example it can perform magic, e.g.:

      .. code-block:: gherkin

        Scenario: My first scenario
          Given I have a step
          When I have another step
          Then I am happy

      Also it can do other stuff as well. Doc will be updated later. :)
      """
    When I run Behat
    Then I should see a passing scenario

  Scenario: Parsing an RST document with a multiple code blocks
    Given I have the RST documentation:
      """
      My awesome feature
      ==================

      This feature is the best one in the world. This document contains many things about it. Have fun reading it.

      For example it can perform magic, e.g.:

      .. code-block:: gherkin

        Scenario: My 1st scenario
          Given I have a step
          When I have another step
          Then I am happy

      It can also do other stuff, here is few example:

      .. code-block:: gherkin

        Scenario: My 1st scenario
          Given I have a step
          When I have another step
          Then I am happy

        Scenario: My 2nd scenario
          Given I have a step
          When I have another step
          Then I am happy

        Scenario: My 3rd scenario
          Given I have a step
          When I have another step
          Then I am happy

      Note that something it does an evil thing, see:

      .. code-block:: gherkin

        Scenario: My 1st scenario
          Given I have a step
          When I have another step
          Then I am happy
      """
    When I run Behat
    Then I should see 5 passing scenarios

  Scenario: Parsing an RST document with a non-gherkin code blocks
    Given I have the RST documentation:
      """
      My awesome feature
      ==================

      This feature is the best one in the world. This document contains many things about it. Have fun reading it.

      For example it can perform magic, e.g.:

      .. code-block:: bash
        $ bin/something run

      It can also do other stuff, here is few example:

      .. code-block:: gherkin

        Scenario: My 1st scenario
          Given I have a step
          When I have another step
          Then I am happy

        Scenario: My 2nd scenario
          Given I have a step
          When I have another step
          Then I am happy

        Scenario: My 3rd scenario
          Given I have a step
          When I have another step
          Then I am happy

      Note that something it does an evil thing, see:

      .. code-block:: gherkin

        Scenario: My 1st scenario
          Given I have a step
          When I have another step
          Then I am happy
      """
    When I run Behat
    Then I should see 4 passing scenarios