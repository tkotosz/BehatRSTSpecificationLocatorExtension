Quick start
===========

Install Behat
-------------

If you didn't install Behat already, then you can install it with composer in the following way:

.. code-block:: bash

    $ composer require --dev behat/behat

For alternative installation options check the `Behat official documentation <https://docs.behat.org/en/latest/quick_start.html#installation>`_

Install the Extension
---------------------

Similarly you can install the extension via composer:

.. code-block:: bash

    $ composer require --dev bex/behat-rst-specification-locator-extension

For more information see the the :doc:`installation section of this documentation </guide/installation>`.

Setup the Behat configuration
-----------------------------

You need to enable the extension in the Behat configuration. Your ``behat.yml`` should look like this:

.. code-block:: yaml

    default:
      extensions:
        Bex\Behat\BehatRSTSpecificationLocatorExtension: ~

For more detailed information see the :doc:`configuration section of this documentation </guide/configuration>`.
