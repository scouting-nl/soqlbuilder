<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

enum Literal: string implements Column
{
    case TODAY = 'TODAY';

    #[\Override]
    public function format(): string
    {
        return $this->value;
    }
}
