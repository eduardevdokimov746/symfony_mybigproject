<?php

declare(strict_types=1);

namespace App\Ship\Parent\Test;

use App\Ship\Trait\CreateTestUserInstanceTrait;
use App\Ship\Trait\FindTestUserFromDBTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as SymfonyKernelTestCase;

abstract class KernelTestCase extends SymfonyKernelTestCase
{
    use FindTestUserFromDBTrait;
    use CreateTestUserInstanceTrait;
}
