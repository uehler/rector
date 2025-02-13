<?php declare(strict_types=1);

namespace Rector\Php\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see http://wiki.php.net/rfc/json_throw_on_error
 * @see https://3v4l.org/5HMVE
 */
final class JsonThrowOnErrorRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Adds JSON_THROW_ON_ERROR to json_encode() and json_decode() to throw JsonException on error',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
json_encode($content);
json_decode($json);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
json_encode($content, JSON_THROW_ON_ERROR);
json_decode($json, null, null, JSON_THROW_ON_ERROR);
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->isName($node, 'json_encode')) {
            return $this->processJsonEncode($node);
        }

        if ($this->isName($node, 'json_decode')) {
            return $this->processJsonDecode($node);
        }

        return null;
    }

    private function processJsonEncode(FuncCall $funcCall): ?FuncCall
    {
        if (isset($funcCall->args[1])) {
            return null;
        }

        $funcCall->args[1] = new Arg($this->createConstFetch('JSON_THROW_ON_ERROR'));

        return $funcCall;
    }

    private function processJsonDecode(FuncCall $funcCall): ?FuncCall
    {
        if (isset($funcCall->args[3])) {
            return null;
        }

        // set default to inter-args
        if (! isset($funcCall->args[1])) {
            $funcCall->args[1] = new Arg($this->createFalse());
        }

        if (! isset($funcCall->args[2])) {
            $funcCall->args[2] = new Arg(new Node\Scalar\LNumber(512));
        }

        $funcCall->args[3] = new Arg($this->createConstFetch('JSON_THROW_ON_ERROR'));

        return $funcCall;
    }

    private function createConstFetch(string $name): ConstFetch
    {
        return new ConstFetch(new Name($name));
    }
}
