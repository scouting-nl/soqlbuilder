<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column;

use PHPUnit\Framework\Attributes\TestWith;
use ScoutingNL\Salesforce\Soql\Column\Fields;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

class FieldsTest extends TestCase
{
    #[TestWith(['FIELDS(ALL)', Fields::ALL])]
    #[TestWith(['FIELDS(CUSTOM)', Fields::CUSTOM])]
    #[TestWith(['FIELDS(STANDARD)', Fields::STANDARD])]
    public function test(string $expected, Fields $fields): void
    {
        self::assertSameIgnoringWhitespace($expected, $fields->format());
    }
}
