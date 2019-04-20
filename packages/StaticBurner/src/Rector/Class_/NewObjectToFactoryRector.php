<?php declare(strict_types=1);

namespace Rector\StaticBurner\Rector\Class_;

use PhpParser\Node;
use Rector\Analyze\ServiceOrObjectAnalyzer;
use Rector\NodeContainer\ParsedNodesByType;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class NewObjectToFactoryRector extends AbstractRector
{
    /**
     * @var ParsedNodesByType
     */
    private $parsedNodesByType;
    /**
     * @var ServiceOrObjectAnalyzer
     */
    private $serviceOrObjectAnalyzer;

    public function __construct(ParsedNodesByType $parsedNodesByType, ServiceOrObjectAnalyzer $serviceOrObjectAnalyzer)
    {
        $this->parsedNodesByType = $parsedNodesByType;
        $this->serviceOrObjectAnalyzer = $serviceOrObjectAnalyzer;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Move new object to factories injected via constructor', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
          return new Product();
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
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
CODE_SAMPLE

            )
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->parsedNodesByType->getClasses() as $class) {
            dump($this->serviceOrObjectAnalyzer->isObject($class));
        }

        dump('todo separate all classes to services and objects');
        die;

        // change the node

        return $node;
    }
}
