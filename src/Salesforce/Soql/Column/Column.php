<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Column;

interface Column
{
    public function format(): string;
}
