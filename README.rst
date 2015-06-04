.. title:: Enabled checker

===============
Enabled checker
===============

This package provides functionality of checking whether object is enabled or disabled.

For example: when you're trying to perform order for a product and you want to make sure that it's possible (object is able to
be ordered). In addition, you can check if category that contains this product could be checked or any other objects that the 
product depends on).

Installation
------------

Add **FivePercent/EnabledChecker** in your composer.json:

.. code-block:: json

    {
        "require": {
            "fivepercent/enabled-checker": "~1.0"
        }
    }


Now tell composer to download the library by running command below:


.. code-block:: bash

    $ php composer.phar update fivepercent/enabled-checker

Basic usage
-----------

Before using **EnabledChecker**, it's needed to create and configure instance of checker.


**Note:** If it's needed to use many checkers, you should use **ChainChecker**

.. code-block:: php

    use FivePercent\Component\EnabledChecker\Checker\ChainChecker;
    use FivePercent\Component\EnabledChecker\EnabledChecker;

    $chainChecker = new ChainChecker();
    $enabledChecker = new EnabledChecker($chainChecker);


When EnabledChecker is created, you can add checkers to **ChainChecker**.

For example, we have a product and category of this product, with following structure:

.. code-block:: php

    class Category
    {
        public $enabled;
    }

    class Product
    {
        /** @var Category */
        public $category;
        public $enabled;
    }

And we want to create a custom checker to check if product is enabled:

.. code-block:: php

    use FivePercent\Component\EnabledChecker\Checker\CheckerInterface;

    class ProductEnabledChecker implements CheckerInterface
    {
        public function isSupported($object)
        {
            return $object instanceof Product;
        }

        public function check($object)
        {
            /** @var Product $object */
            if ($object->category && !$object->category->enabled) {
                // Category is not enabled
                return false;
            }

            if (!$object->enabled) {
                // Product is not enabled
                return false;
            }

            return true;
        }
    }

And add this checker instance to **ChainChecker**:

.. code-block:: php

    $chainChecker->addChecker(new ProductEnabledChecker());

After this operation, we can check whether product is enabled or not ;)

**Attention:** method ``check`` throws exception ``FivePercent\Component\EnabledChecker\Exception\NotEnabledException``, if checker returns false.

#. **Product is disabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = false;

        $checker->check($product); // Throws exception


#. **Product is enabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = true;

        $enabledChecker->check($product); // OK

#. **Category of product are disabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = true;
        $product->category = new Category();
        $product->category->enabled = false;

        $enabledChecker->check($product); // Throws exception

#. **Category and product are enabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = true;
        $product->category = new Category();
        $product->category->enabled = true;

        $enabledChecker->check($product); // All OK


**Note:** If you want to throw custom exception, you can implement interface ``FivePercent\Component\EnabledChecker\ExceptionAwareInterface``.

.. code-block:: php

    use FivePercent\Component\EnabledChecker\ExceptionAwareInterface;

    class Product implements ExceptionAwareInterface
    {
        /** @var Category */
        public $category;
        public $enabled;

        public function getExceptionForNotEnabled()
        {
            if ($this->category && !$this->category->enabled) {
                return new \RuntimeException('Category disabled!');
            }

            if (!$this->enabled) {
                return new \RuntimeException('Project disabled.');
            }

            return null;
        }
    }

**Note:** In simple objects, you can implement ``FivePercent\Component\EnabledChecker\EnabledIndicateInterface``, that doesn't require checker creation
