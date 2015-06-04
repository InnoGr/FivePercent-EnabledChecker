.. title:: Enabled checker

===============
Enabled checker
===============

With this package, you can check objects of enabled.

As example: you want check product of enabled before create order for this product. But, product will be disabled, if
category is disabled or another objects in hierarchical is disabled.

Installation
------------

Add **FivePercent/EnabledChecker** in your composer.json:

.. code-block:: json

    {
        "require": {
            "fivepercent/enabled-checker": "~1.0"
        }
    }


Now tell composer to download the library by running the command:


.. code-block:: bash

    $ php composer.phar update fivepercent/enabled-checker

Basic usage
-----------

Before use **EnabledChecker**, you must create and configure instance.


**Note:** For use many checkers, please use **ChainChecker**

.. code-block:: php

    use FivePercent\Component\EnabledChecker\Checker\ChainChecker;
    use FivePercent\Component\EnabledChecker\EnabledChecker;

    $chainChecker = new ChainChecker();
    $enabledChecker = new EnabledChecker($chainChecker);


After create EnabledChecker instance, you can add checkers to **ChainChecker**.

As example, we have a product and category of this product, with structure:

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

And we want create a custom checker for check product of enabled:

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
                // Category not enabled
                return false;
            }

            if (!$object->enabled) {
                // Product not enabled
                return false;
            }

            return true;
        }
    }

And add this checker instance to **ChainChecker**:

.. code-block:: php

    $chainChecker->addChecker(new ProductEnabledChecker());

After, we can check of enabled product ;)

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

        $enabledChecker->check($product); // All OK

#. **Category of product is disabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = true;
        $product->category = new Category();
        $product->category->enabled = false;

        $enabledChecker->check($product); // Throws exception

#. **Category and product is enabled**

    .. code-block:: php

        $product = new Product();
        $product->enabled = true;
        $product->category = new Category();
        $product->category->enabled = true;

        $enabledChecker->check($product); // All OK


**Note:** If you want throws custom exception, you can implement ``FivePercent\Component\EnabledChecker\ExceptionAwareInterface``
for object.

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

**Note:** In simple objects, you can implement ``FivePercent\Component\EnabledChecker\EnabledIndicateInterface``, then need not
create checker
