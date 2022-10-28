<?php

namespace App\Ship\PhpUnitExtension;

use App\Ship\Helper\RemoveFilesFromTmpDir;
use PHPUnit\Runner\AfterLastTestHook;

class ClearTmpFilesExtension implements AfterLastTestHook
{
    public function executeAfterLastTest(): void
    {
        RemoveFilesFromTmpDir::run();
    }
}
