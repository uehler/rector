<?php

namespace Rector\StaticBurner\Tests\Rector\Class_\NewObjectToFactoryRector\Fixture;

class SomeClass
{
    public function run()
    {
          return new Product();
    }
}

?>
-----
<?php

namespace Rector\StaticBurner\Tests\Rector\Class_\NewObjectToFactoryRector\Fixture;

use SomeAnother\AnotherClass;

class SomeClass
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    public function __construct(ProductFactory $productFactory)
    {
        $this->productFactory = productFactory;
    }

    public function run()
    {
          return $this->productFactory->create();
    }
}

?>
