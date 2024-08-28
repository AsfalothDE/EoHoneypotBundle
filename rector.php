<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');
    $rectorConfig->paths([
        __DIR__ . '/'
    ]);
    $rectorConfig->skip([
        __DIR__ . '/Resources',
        __DIR__ . '/Tests',
        __DIR__ . '/var',
        __DIR__ . '/vendor',
    ]);

    // Define what rule sets will be applied
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SymfonySetList::SYMFONY_70,
    ]);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
};
