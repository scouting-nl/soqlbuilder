<?php
declare(strict_types=1);

use Nerdman\CodeStyle\Config\Php83;
use PhpCsFixer\Finder;

return new Php83(
    Finder::create()
        ->in([
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ])
        ->append(['.php-cs-fixer.dist.php'])
        ->notContains('/@nolint/'),
    cacheFile: '.php-cs-fixer.cache',
);
