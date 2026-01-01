<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/src/Entity',   // SetList::CODE_QUALITY macht da Mist
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        phpunitCodeQuality: true,
        doctrineCodeQuality: true,
        symfonyCodeQuality: true
    )
    ->withPhpSets(php84: true)
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true)
    ->withImportNames()
    ->withCache('var/cache/rector');
