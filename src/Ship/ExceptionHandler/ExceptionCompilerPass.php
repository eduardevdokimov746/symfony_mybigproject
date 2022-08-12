<?php

namespace App\Ship\ExceptionHandler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExceptionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $extensions = $container->findTaggedServiceIds('app.exception');

        $container->getDefinition(ExceptionMappingResolver::class)
            ->addArgument(array_map(fn ($argsArray) => array_pop($argsArray), $extensions));
    }
}