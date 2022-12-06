<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Helper;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
#[Assert\Callback]
class ConstraintTestClass
{
    #[Assert\Type('int')]
    #[Assert\GreaterThan(value: 10)]
    public int $prop;

    #[Assert\NotBlank]
    #[Assert\IsTrue]
    public function met(): void
    {
    }
}
