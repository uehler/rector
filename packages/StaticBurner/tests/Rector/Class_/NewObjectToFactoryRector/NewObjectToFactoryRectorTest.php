<?php declare(strict_types=1);

namespace Rector\StaticBurner\Tests\Rector\Class_\NewObjectToFactoryRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class NewObjectToFactoryRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([
            __DIR__ . '/Fixture/fixture.php.inc'
        ]);
    }

    protected function getRectorClass(): string
    {
        return \Rector\StaticBurner\Rector\Class_\NewObjectToFactoryRector::class;
    }
}
