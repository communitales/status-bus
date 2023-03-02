<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        //__DIR__.'/tests',
    ]);
    $rectorConfig->sets([
        SetList::PHP_82,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
    ]);
    $rectorConfig->importNames();
};
