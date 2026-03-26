## PHP builder for the Salesforce Object Query Language (SOQL)

Copyright © 2025-2026 Scouting Nederland (https://www.scouting.nl)

### Introduction

This PHP library can be used to programmatically build a SOQL query.

### Installation

```shell
composer require scouting-nl/soqlbuilder
```

### Example

```php
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Salesforce\Soql\Where;

$query = SoqlBuilder::select('Account')
    ->columns('Id', 'Name')
    ->where(
        Where::equals('RecordType.DeveloperName', 'Scouting_Organisations'),
        Where::equals('Active__c', true),
        Where::in(
            'Id',
            SoqlBuilder::select('Organisation_Unit__c')
                ->columns('Organisation__c')
                ->where('Type__c', 'scouts'),
        ),
    );
```

### License

This library is licensed under the [MIT license](LICENSE)
