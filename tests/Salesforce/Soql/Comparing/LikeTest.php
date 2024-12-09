<?php
declare(strict_types=1);

namespace App\Tests\Salesforce\Soql\Comparing;

use App\Salesforce\Soql\Condition\Comparing\Like;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{
    #[DataProvider('provideLike')]
    public function testLike(string $expected, Like $like): void
    {
        self::assertEquals(
            \preg_replace('/(\s|[\n\r])+/', ' ', \trim($expected)),
            \preg_replace('/(\s|[\n\r])+/', ' ', (string)$like),
        );
    }

    /**
     * @return \Generator<array-key, array{string, Like}>
     */
    public static function provideLike(): \Generator
    {
        yield [
            "a LIKE '%v1%'",
            new Like('a', '%v1%'),
        ];

        yield [
            "a LIKE '%v1%'",
            new Like('a', new class implements \Stringable {
                public function __toString(): string
                {
                    return '%v1%';
                }
            }),
        ];

        yield [
            "(NOT a LIKE '%v1%')",
            new Like('a', '%v1%', negate: true),
        ];

        yield [
            "(NOT a LIKE '%v1%')",
            new Like(
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
