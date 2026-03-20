<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Like;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Like::class)]
class LikeTest extends TestCase
{
    /**
     * @param callable(): Like $like
     */
    #[DataProvider('provideLike')]
    public function testLike(string $expected, callable $like): void
    {
        self::assertSameIgnoringWhitespace($expected, (string)$like());
    }

    /**
     * @return \Generator<array-key, array{string, callable(): Like}>
     */
    public static function provideLike(): \Generator
    {
        yield [
            "a LIKE '%v1%'",
            static fn () => new Like('a', '%v1%'),
        ];

        yield [
            "a LIKE '%v1%'",
            static fn () => new Like('a', new class implements \Stringable {
                public function __toString(): string
                {
                    return '%v1%';
                }
            }),
        ];

        yield [
            "(NOT a LIKE '%v1%')",
            static fn () => new Like('a', '%v1%', negate: true),
        ];

        yield [
            "(NOT a LIKE '%v1%')",
            static fn () => new Like(
                'a',
                new class implements \Stringable {
                    public function __toString(): string
                    {
                        return '%v1%';
                    }
                },
                negate: true,
            ),
        ];
    }
}
