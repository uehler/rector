<?php declare(strict_types=1);

namespace Rector\Shopware\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use function explode;
use function str_replace;
use function ucfirst;

final class Shopwareevents extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        // what does this do?
        // minimalistic before/after sample - to explain in code
        return new RectorDefinition('Change method calls from set* to change*.', [
            new CodeSample('$user->setPassword("123456");', '$user->changePassword("123456");'),
        ]);
    }


    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        // what node types we look for?
        // pick any node from https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md
        return [MethodCall::class];
    }


    /**
     * @param MethodCall $node - we can add "MethodCall" type here, because only this node is in "getNodeTypes()"
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'notifyUntil')) {
            return null;
        }

        /** @var Node\Arg $argument */
        $argument = $node->args[0];

        $eventName = $argument->value;
        if (!($eventName instanceof Node\Scalar\String_)) {
            //            echo "\n";
            //            print_r($eventName);
            //            echo "\n";
            return null;
        }

        list($shopware, $modules, $moduleName, $camelCasedEventName) = explode('_', $eventName->value, 4);

        $fqn = 'Shopware\\Core\\Events\\' . ucfirst($moduleName) . '\\' . str_replace('_', '', $camelCasedEventName) . 'Event';
        $path = 'engine/Shopware/Core/Events/' . ucfirst($moduleName) . '/' . str_replace('_', '', $camelCasedEventName) . 'Event';

        echo "\n";
        print_r($eventName->value);
        echo "\n";
        print_r('FQN would be ' . $fqn);
        print_r('Path would be ' . $fqn);

        $class=new Node\Stmt\Class_($fqn);
        $class->extends='\Enlight_Event_EventArgs';

        //        die(__METHOD__.' Zeile '.__LINE__);
        //        $newMethodCallName = Strings::replace($methodCallName, '#^set#', 'change');
        //
        //        $node->name = new Identifier($newMethodCallName);

        // return $node if you modified it
        return $node;
    }
}