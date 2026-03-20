<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Like;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Like::class)]
class LikeTest extends TestCase
{
    #[TestWith(['%v1%', false, "a LIKE '%v1%'"])]
    #[TestWith(['%v1%', true, "(NOT a LIKE '%v1%')"])]
    public function testLike(string $value, bool $negate, string $expected): void
    {
        self::assertSameIgnoringWhitespace($expected, new Like('a', $value, negate: $negate));
    }

    #[TestWith([false, "a LIKE '%v1%'"])]
    #[TestWith([true, "(NOT a LIKE '%v1%')"])]
    public function testLikeWithStringable(bool $negate, string $expected): void
    {
        self::assertEquals(
            $expected,
            new Like(
                'a',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return '%v1%';
                    }
                },
                negate: $negate,
            ),
        );
    }
}
