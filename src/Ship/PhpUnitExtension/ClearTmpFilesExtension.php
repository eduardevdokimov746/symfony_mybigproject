<?php

namespace App\Ship\PhpUnitExtension;

use App\Ship\Helper\File;
use PHPUnit\Runner\AfterLastTestHook;

class ClearTmpFilesExtension implements AfterLastTestHook
{
    public function executeAfterLastTest(): void
    {
        File::removeTmpFiles();
    }
}
