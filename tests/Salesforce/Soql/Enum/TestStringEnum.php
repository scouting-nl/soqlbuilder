<?php
declare(strict_types=1);

namespace App\Tests\Salesforce\Soql\Enum;

enum TestStringEnum: string
{
    case STR1 = 'str1';
    case STR2 = 'str2';
}
