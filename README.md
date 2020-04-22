BehatRSTSpecificationLocatorExtension
=====================================

[![License](https://poser.pugx.org/bex/behat-rst-specification-locator-extension/license)](https://packagist.org/packages/bex/behat-rst-specification-locator-extension)
[![Latest Stable Version](https://poser.pugx.org/bex/behat-rst-specification-locator-extension/version)](https://packagist.org/packages/bex/behat-rst-specification-locator-extension)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tkotosz/BehatRSTSpecificationLocatorExtension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/BehatRSTSpecificationLocatorExtension/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tkotosz/BehatRSTSpecificationLocatorExtension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/BehatRSTSpecificationLocatorExtension/build-status/master)
[![Documentation Status](https://readthedocs.org/projects/behat-rst-specification-locator-extension/badge/?version=latest)](https://behat-rst-specification-locator-extension.readthedocs.io/en/latest/?badge=latest)

`BehatRSTSpecificationLocatorExtension` is a [Behat](https://behat.org) extension which allows loading specifications from [reStructuredText](https://docutils.sourceforge.io/rst.html) documentation.

Installation
------------

The recommended installation method is through [Composer](https://getcomposer.org):

```bash
composer require --dev bex/behat-rst-specification-locator-extension
```

Documentation
-------------

The official documentation is available [here](https://behat-rst-specification-locator-extension.readthedocs.io/).


TODO
----
- Parse code blocks embedded inside a quote block (happens when indenting the code block)
- Use the GherkinExtension's cache configuration if possible otherwise add proper cache configuration for the extension
- Add tests for running behat with file path as parameter
- Add tests for running behat with folder path as parameter
- Add tests for running behat without path parameter (cases: path configured in suite, path not configured)
- Make sure recursive file discovery works under any given path
- Add parse error reporting (proper error handling)
- Make sure dry-run prints the scenarios
- Fix line number reference when test fails (it should refer to the line number in the original document)
- Add the ability to run specific scenario by referring to line number (e.g. `bin/behat docs/my_doc.rst:12`)
- Ignore documentation files which doesn't contain gherkin code blocks
- Implement feature to ignore some code blocks from the document since probably not all scenario written in the document should be executed as automated test (note: probably can be done simply with tagging the scenario - options to just skip or exclude it entirely)
- Investigate: Is there a report generator extension already which could be used to enhance the documentation with the scenario status (passing/skipped/failed)?
