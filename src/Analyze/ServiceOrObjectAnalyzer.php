<?php declare(strict_types=1);

namespace Rector\Analyze;

use PhpParser\Node\Stmt\Class_;

final class ServiceOrObjectAnalyzer
{
    public function isObject(Class_ $class): bool
    {
        return false;

    }
}
