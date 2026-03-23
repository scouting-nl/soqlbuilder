<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value;

interface Value
{
    public function format(): string;
}
