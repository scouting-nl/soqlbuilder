<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value;

final readonly class Literal implements Value
{
    public function __construct(private string $value)
    {
    }

    #[\Override]
    public function format(): string
    {
        return $this->value;
    }
}
