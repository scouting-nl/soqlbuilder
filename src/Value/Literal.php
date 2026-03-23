<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value;

enum Literal: string implements Value
{
    case TODAY = 'TODAY';

    #[\Override]
    public function format(): string
    {
        return $this->value;
    }
}
