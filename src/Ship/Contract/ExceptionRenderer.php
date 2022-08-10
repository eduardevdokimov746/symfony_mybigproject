<?php

namespace App\Ship\Contract;

use App\Ship\ExceptionHandler\ExceptionMapping;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

interface ExceptionRenderer
{
    public function render(ExceptionMapping $mapping): FlattenException;
}