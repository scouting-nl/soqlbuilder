<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

interface Column
{
    public function format(): string;
}
